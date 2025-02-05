<div>
    <div class="container my-4">
        <form wire:submit.prevent="updateProfileInformation" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Profile Information') }}</h5>
                    <p class="text-muted small">
                        {{ __('Update your account\'s profile information and email address.') }}
                    </p>
                </div>
                <div class="card-body">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div x-data="{photoName: null, photoPreview: null}" class="mb-3">
                            <!-- Profile Photo File Input -->
                            <input type="file" id="photo" class="d-none"
                                   wire:model.live="photo"
                                   x-ref="photo"
                                   x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                   " />

                            <label for="photo" class="form-label">{{ __('Photo') }}</label>

                            <!-- Current Profile Photo -->
                            <div class="mt-2" x-show="! photoPreview">
                                <img src="{{ $this->user->profile_photo_url }}" 
                                     alt="{{ $this->user->name }}" 
                                     class="rounded-circle"
                                     style="width:80px; height:80px; object-fit:cover;">
                            </div>

                            <!-- New Profile Photo Preview -->
                            <div class="mt-2" x-show="photoPreview" style="display: none;">
                                <span class="d-block rounded-circle"
                                      style="width:80px; height:80px; background-size: cover; background-repeat: no-repeat; background-position: center;"
                                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                            </div>

                            <button type="button" class="btn btn-secondary mt-2 me-2" 
                                    x-on:click.prevent="$refs.photo.click()">
                                {{ __('Select A New Photo') }}
                            </button>

                            @if ($this->user->profile_photo_path)
                                <button type="button" class="btn btn-secondary mt-2" wire:click="deleteProfilePhoto">
                                    {{ __('Remove Photo') }}
                                </button>
                            @endif

                            <div class="invalid-feedback d-block mt-2">
                                <x-input-error for="photo" />
                            </div>
                        </div>
                    @endif

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control" 
                               wire:model="state.name" required autocomplete="name" />
                        <div class="invalid-feedback d-block">
                            <x-input-error for="name" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control" 
                               wire:model="state.email" required autocomplete="username" />
                        <div class="invalid-feedback d-block">
                            <x-input-error for="email" />
                        </div>

                        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                            <p class="small mt-2">
                                {{ __('Your email address is unverified.') }}
                                <button type="button" class="btn btn-link p-0 align-baseline" 
                                        wire:click.prevent="sendEmailVerification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if ($this->verificationLinkSent)
                                <p class="mt-2 fw-medium small text-success">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end align-items-center">
                    <span class="text-success me-3" wire:loading.remove wire:target="saved">
                        {{ __('Saved.') }}
                    </span>
                    <button type="submit" class="btn btn-primary" 
                            wire:loading.attr="disabled" wire:target="photo">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
