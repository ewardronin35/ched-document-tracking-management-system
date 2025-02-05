<div>
    <div class="container my-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ __('Browser Sessions') }}</h5>
                <p class="card-subtitle text-muted small">
                    {{ __('Manage and log out your active sessions on other browsers and devices.') }}
                </p>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    {{ __('If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.') }}
                </p>

                @if (count($this->sessions) > 0)
                    <div class="mt-4">
                        <!-- Other Browser Sessions -->
                        @foreach ($this->sessions as $session)
                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    @if ($session->agent->isDesktop())
                                        <!-- Desktop SVG Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                                             viewBox="0 0 24 24" stroke-width="1.5" 
                                             stroke="currentColor" class="bi bi-display-fill text-secondary" width="32" height="32">
                                            <path d="M3 4.5A1.5 1.5 0 014.5 3h15A1.5 1.5 0 0121 4.5v9A1.5 1.5 0 0119.5 15h-15A1.5 1.5 0 013 13.5v-9zM3 15.75h18v1.5H3v-1.5z"/>
                                        </svg>
                                    @else
                                        <!-- Mobile SVG Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                                             viewBox="0 0 24 24" stroke-width="1.5" 
                                             stroke="currentColor" class="bi bi-phone text-secondary" width="32" height="32">
                                            <path d="M8.25 3A2.25 2.25 0 006 5.25v13.5A2.25 2.25 0 008.25 21h7.5A2.25 2.25 0 0018 18.75V5.25A2.25 2.25 0 0015.75 3h-7.5zM12 18.75a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                                        </svg>
                                    @endif
                                </div>

                                <div class="ms-3">
                                    <div class="small text-muted">
                                        {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }} - 
                                        {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $session->ip_address }},
                                        @if ($session->is_current_device)
                                            <span class="text-success fw-semibold">{{ __('This device') }}</span>
                                        @else
                                            {{ __('Last active') }} {{ $session->last_active }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="d-flex align-items-center mt-4">
                    <button wire:click="confirmLogout" wire:loading.attr="disabled" class="btn btn-primary">
                        {{ __('Log Out Other Browser Sessions') }}
                    </button>

                    <span class="ms-3 text-success" wire:loading.remove wire:target="confirmLogout">
                        {{ __('Done.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Out Other Devices Confirmation Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" 
         wire:model.live="confirmingLogout" id="logoutOtherSessionsModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Log Out Other Browser Sessions') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}
                    </p>

                    <div class="mb-3" 
                         x-data="{}" 
                         x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                        <input type="password" 
                               class="form-control" 
                               autocomplete="current-password"
                               placeholder="{{ __('Password') }}"
                               x-ref="password"
                               wire:model="password"
                               wire:keydown.enter="logoutOtherBrowserSessions" />
                        <div class="invalid-feedback d-block">
                            <x-input-error for="password" class="mt-2" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" 
                            class="btn btn-secondary" 
                            wire:click="$toggle('confirmingLogout')" 
                            wire:loading.attr="disabled"
                            data-bs-dismiss="modal">
                        {{ __('Cancel') }}
                    </button>
                    <button type="button" 
                            class="btn btn-primary" 
                            wire:click="logoutOtherBrowserSessions" 
                            wire:loading.attr="disabled">
                        {{ __('Log Out Other Browser Sessions') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
