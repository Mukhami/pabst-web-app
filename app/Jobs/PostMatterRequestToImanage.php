<?php

namespace App\Jobs;

use App\Models\MatterRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class PostMatterRequestToImanage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public MatterRequest $matterRequest;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(MatterRequest $matterRequest, User $user)
    {
        $this->matterRequest = $matterRequest;
        $this->user = $user;
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        $matterRequest = $this->matterRequest;
        $clientId = null;

        $baseUrl = "https://iman.alamaworks.com/api/v1";
        // login
        $credentials = [
            'email' => config('app.imanage_auth.email'),
            'password' => config('app.imanage_auth.password'),
        ];
        $authRequest = Http::asJson()->acceptJson()->post($baseUrl.'/login', $credentials);
        if ($authRequest->ok() && Arr::exists($authRequest, 'token')){
            $token = $authRequest['token']['access_token'];
        } else {
            throw new \Exception('Failed to retrieve access token from API. Response '. $authRequest);
        }
        // check if client exists
        $clientCheckRequest = Http::withToken($token)->acceptJson()->post($baseUrl.'/clients/fetch-using-key/'.$matterRequest->ppg_ref);
        if ($clientCheckRequest->notFound()){
            // post client
            $clientData = [
                'library_id' => config('app.default_library'),
                'client_key' => $matterRequest->ppg_ref,
                'client_name' => $matterRequest->client_name,
                'enabled' => true,
                'hipaa' => false,
            ];

            $clientRequest = Http::withToken($token)->acceptJson()->post($baseUrl.'/clients/create', $clientData);
            if ($clientRequest->ok() or $clientRequest->created()){
                $clientId = $clientRequest['data']['id'];
            }
        } elseif($clientCheckRequest->ok()) {
            $clientId = $clientCheckRequest['data']['id'];
        }
        if($clientId){
            // post matter
            $matterData = [
                'client_id' => $clientId,
                'practice_area_id' => config('app.default_practice_area'),
                'template_id' => config('app.default_template'),
                'matter_key' => $matterRequest->ppg_client_matter_no,
                'description' => $matterRequest->title_of_invention,
                'enabled' => true,
                'hipaa' => false,
            ];

            $matterAPIRequest = Http::withToken($token)->acceptJson()->post($baseUrl.'/matters/create', $matterData);
            if ($matterAPIRequest->ok() or $matterAPIRequest->created()){
                // save response
                $matterRequest->matter_create_response = $matterAPIRequest->json();
                $matterRequest->save();
            } else {
                throw new \Exception('Failed to create matter on API. Response '. $matterAPIRequest);
            }
        } else {
            throw new \Exception('Failed to retrieve client ID from API.');
        }


    }
}
