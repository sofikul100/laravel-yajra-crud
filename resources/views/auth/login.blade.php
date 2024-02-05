<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <h1 class="mb-3 font-bold text-sky-600 text-center"> CRUD APPLICATION LOGIN FORM </h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" placeholder="Enter Email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            placeholder="*******"
                            
                            required autocomplete="current-password" />
            <div class="absolute right-2 top-[34px]">
                    <span class="toggle-password" onclick="togglePasswordVisibility()">
                    <i class="fa fa-eye text-gray-500 text-[18px] cursor-pointer hover:text-sky-600"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>



        <div class="flex items-center justify-start mt-4 ">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>


    <div class="mt-6 border px-2 py-2 rounded">
          <div class="flex items-center justify-between">
                <div class="font-[500]">test@test.com</div>
                <div class="font-[500]">123456</div>
                <div>
                    <button id="copy__button" class="bg-green-600 px-4 rounded py-1 text-white font-[500]">Copy</button>
                </div>
          </div>
    </div>

</x-guest-layout>


<script>
$(document).ready(function(){
    $('#copy__button').click(function(){
        var email = 'test@test.com';
        var password = '123456';
        
        $('#email').val(email);
        $('#password').val(password);
    });
});


//=======password show hide logic  here==========//
function togglePasswordVisibility() {
        var passwordField = $('#password');
        var passwordFieldType = passwordField.attr('type');
        var toggleButton = $('.toggle-password i');

        // Toggle password visibility
        if (passwordFieldType === 'password') {
            passwordField.attr('type', 'text');
            toggleButton.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            toggleButton.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    }

</script>

