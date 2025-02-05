<x-guest-layout>
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
        <!-- Authentication Card -->
        <div class="card shadow-lg border-0 rounded-lg p-4" style="max-width: 450px; width: 100%;">
            <div class="card-body">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <x-authentication-card-logo />
                </div>

                <!-- Alpine.js Logic -->
                <div x-data="{ recovery: false }">
                    <!-- Authentication Code Prompt -->
                    <p class="mb-4 text-muted small" x-show="!recovery">
                        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                    </p>

                    <!-- Recovery Code Prompt -->
                    <p class="mb-4 text-muted small" x-cloak x-show="recovery">
                        {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
                    </p>

                    <!-- Validation Errors -->
                    <x-validation-errors class="alert alert-danger mb-4" />

                    <!-- Form -->
                    <form method="POST" action="{{ route('two-factor.login') }}">
                        @csrf

                        <!-- Code Input -->
                        <div class="mb-3" x-show="!recovery">
                            <label for="code" class="form-label fw-bold">{{ __('Code') }}</label>
                            <input id="code" type="text" name="code" class="form-control" inputmode="numeric" autofocus x-ref="code" autocomplete="one-time-code">
                        </div>

                        <!-- Recovery Code Input -->
                        <div class="mb-3" x-cloak x-show="recovery">
                            <label for="recovery_code" class="form-label fw-bold">{{ __('Recovery Code') }}</label>
                            <input id="recovery_code" type="text" name="recovery_code" class="form-control" x-ref="recovery_code" autocomplete="one-time-code">
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="button" class="btn btn-link text-decoration-none p-0 text-muted small"
                                    x-show="!recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                                {{ __('Use a recovery code') }}
                            </button>

                            <button type="button" class="btn btn-link text-decoration-none p-0 text-muted small"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                                {{ __('Use an authentication code') }}
                            </button>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-guest-layout>
