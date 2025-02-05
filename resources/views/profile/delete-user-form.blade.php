<div>
    <!-- Delete Account Card -->
    <div class="card mb-4">
        <div class="card-header fw-bold">
            {{ __('Delete Account') }}
        </div>
        <div class="card-body">
            <p class="text-muted small">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </p>

            <button 
                class="btn btn-danger" 
                wire:click="confirmUserDeletion" 
                wire:loading.attr="disabled"
                data-bs-toggle="modal" 
                data-bs-target="#confirmDeletionModal"
            >
                {{ __('Delete Account') }}
            </button>
        </div>
    </div>

    <!-- Delete User Confirmation Modal -->
    <div 
        class="modal fade" 
        id="confirmDeletionModal" 
        tabindex="-1" 
        aria-labelledby="confirmDeletionModalLabel" 
        aria-hidden="true"
        wire:ignore.self
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeletionModalLabel">
                        {{ __('Delete Account') }}
                    </h5>
                    <button 
                        type="button" 
                        class="btn-close" 
                        data-bs-dismiss="modal" 
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}

                    <!-- Password Input -->
                    <div class="mt-3">
                        <input 
                            type="password" 
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="{{ __('Password') }}"
                            wire:model.defer="password" 
                            wire:keydown.enter="deleteUser"
                        >
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button 
                        type="button" 
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        {{ __('Cancel') }}
                    </button>
                    <button 
                        type="button" 
                        class="btn btn-danger ms-2" 
                        wire:click="deleteUser" 
                        wire:loading.attr="disabled"
                    >
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
