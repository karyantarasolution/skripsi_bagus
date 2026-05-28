@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-[#0b0c10] border border-gray-700 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 rounded-xl text-white px-4 py-3 transition-colors']) }}>
