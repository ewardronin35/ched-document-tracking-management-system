<div>
    <div class="container my-4">
        <form wire:submit.prevent="updatePassword">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Update Password') }}</h5>
                    <p class="mb-0 text-muted small">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                    </p>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                        <input id="current_password" 
                               type="password" 
                               class="form-control" 
                               wire:model="state.current_password" 
                               autocomplete="current-password" />
                        <div class="invalid-feedback d-block">
                            <x-input-error for="current_password" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('New Password') }}</label>
                        <input id="password" 
                               type="password" 
                               class="form-control" 
                               wire:model="state.password" 
                               autocomplete="new-password" />
                        <div class="invalid-feedback d-block">
                            <x-input-error for="password" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" 
                               type="password" 
                               class="form-control" 
                               wire:model="state.password_confirmation" 
                               autocomplete="new-password" />
                        <div class="invalid-feedback d-block">
                            <x-input-error for="password_confirmation" />
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end align-items-center">
                    <span class="text-success me-3" wire:loading.remove wire:target="saved">
                        {{ __('Saved.') }}
                    </span>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
