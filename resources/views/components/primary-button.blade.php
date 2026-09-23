<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-xl px-5 py-2.5 text-xs font-bold uppercase tracking-widest text-white transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2']) }}
    style="background: #2196f3; box-shadow: 0 10px 22px -10px rgba(33,150,243,.35);">
    {{ $slot }}
</button>
