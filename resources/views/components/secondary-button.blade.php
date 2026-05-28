<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-[#0b0c10] border border-gray-700 rounded-xl font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-[#1a1c26] hover:text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-[#161821] disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
