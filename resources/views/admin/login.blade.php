<x-layouts.guest title="Login Admin">
    {{-- Logo --}}
    <div class="mb-6 flex justify-center">
        <span class="grid size-16 place-items-center rounded-full bg-brand-600 text-xl font-bold text-white">L</span>
    </div>

    <div class="rounded-2xl bg-surface-muted p-8 shadow-sm">
        <h1 class="text-center text-lg font-semibold text-ink">Please Log in</h1>

        @if (session('info'))
            <p class="mt-4 rounded-lg bg-brand-50 px-3 py-2 text-center text-sm text-brand-700">
                {{ session('info') }}
            </p>
        @endif

        <form action="{{ route('admin.login.attempt') }}" method="POST" class="mt-6 space-y-3">
            @csrf

            <x-input

                name="username"
                placeholder="Username"
                aria-label="Username"
                autocomplete="username"

                name="email"
                placeholder="Email"
                aria-label="Email"
                autocomplete="email"

                autofocus
                class="rounded-full bg-surface"
            />

            <x-input
                name="password"
                type="password"
                placeholder="Password"
                aria-label="Password"
                autocomplete="current-password"
                class="rounded-full bg-surface"
            />

            <p class="text-xs text-brand-600">
                forgot your password? Please contact your manager.
            </p>

            <x-button type="submit" class="w-full rounded-full">Enter</x-button>
        </form>
    </div>
</x-layouts.guest>
