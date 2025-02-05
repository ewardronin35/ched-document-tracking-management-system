<div>
    <div class="container my-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Two Factor Authentication') }}</h5>
                <p class="text-muted small">
                    {{ __('Add additional security to your account using two factor authentication.') }}
                </p>
            </div>
            <div class="card-body">
                <h3 class="fw-bold text-dark">
                    @if ($this->enabled)
                        @if ($showingConfirmation)
                            {{ __('Finish enabling two factor authentication.') }}
                        @else
                            {{ __('You have enabled two factor authentication.') }}
                        @endif
                    @else
                        {{ __('You have not enabled two factor authentication.') }}
                    @endif
                </h3>

                <div class="mt-3 text-muted">
                    <p>
                        {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
                    </p>
                </div>

                @if ($this->enabled)
                    @if ($showingQrCode)
                        <div class="mt-4 text-muted">
                            <p class="fw-semibold">
                                @if ($showingConfirmation)
                                    {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                                @else
                                    {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                                @endif
                            </p>
                        </div>

                        <div class="mt-4 p-2 d-inline-block bg-white border">
                            {!! $this->user->twoFactorQrCodeSvg() !!}
                        </div>

                        <div class="mt-4 text-muted">
                            <p class="fw-semibold">
                                {{ __('Setup Key') }}: {{ decrypt($this->user->two_factor_secret) }}
                            </p>
                        </div>

                        @if ($showingConfirmation)
                            <div class="mt-4">
                                <div class="mb-3">
                                    <label for="code" class="form-label">{{ __('Code') }}</label>
                                    <input id="code" type="text" name="code" 
                                           class="form-control w-50"
                                           inputmode="numeric" autofocus autocomplete="one-time-code"
                                           wire:model="code"
                                           wire:keydown.enter="confirmTwoFactorAuthentication" />
                                    <div class="invalid-feedback d-block">
                                        <x-input-error for="code" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if ($showingRecoveryCodes)
                        <div class="mt-4 text-muted">
                            <p class="fw-semibold">
                                {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                            </p>
                        </div>

                        <div class="mt-4 p-4 bg-light rounded" style="max-width: 40rem;">
                            <div class="row row-cols-2 g-2">
                                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                                    <div class="col">
                                        <code>{{ $code }}</code>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <div class="mt-5">
                    @if (! $this->enabled)
                        <x-confirms-password wire:then="enableTwoFactorAuthentication">
                            <button type="button" wire:loading.attr="disabled" class="btn btn-primary">
                                {{ __('Enable') }}
                            </button>
                        </x-confirms-password>
                    @else
                        @if ($showingRecoveryCodes)
                            <x-confirms-password wire:then="regenerateRecoveryCodes">
                                <button type="button" class="btn btn-secondary me-3">
                                    {{ __('Regenerate Recovery Codes') }}
                                </button>
                            </x-confirms-password>
                        @elseif ($showingConfirmation)
                            <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                                <button type="button" class="btn btn-primary me-3" wire:loading.attr="disabled">
                                    {{ __('Confirm') }}
                                </button>
                            </x-confirms-password>
                        @else
                            <x-confirms-password wire:then="showRecoveryCodes">
                                <button type="button" class="btn btn-secondary me-3">
                                    {{ __('Show Recovery Codes') }}
                                </button>
                            </x-confirms-password>
                        @endif

                        @if ($showingConfirmation)
                            <x-confirms-password wire:then="disableTwoFactorAuthentication">
                                <button type="button" class="btn btn-secondary" wire:loading.attr="disabled">
                                    {{ __('Cancel') }}
                                </button>
                            </x-confirms-password>
                        @else
                            <x-confirms-password wire:then="disableTwoFactorAuthentication">
                                <button type="button" class="btn btn-danger" wire:loading.attr="disabled">
                                    {{ __('Disable') }}
                                </button>
                            </x-confirms-password>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
