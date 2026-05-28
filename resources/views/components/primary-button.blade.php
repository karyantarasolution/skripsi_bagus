<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full py-3.5 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-500 hover:to-pink-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(168,85,247,0.4)] transition-all transform hover:scale-[1.02] active:scale-95 uppercase tracking-widest text-xs']) }}>
    {{ $slot }}
</button>
