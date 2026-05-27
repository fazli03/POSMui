// tailwind.config.js
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./app/Filament/**/*.php",
        "./app/Livewire/**/*.php",
        "./resources/**/*.js",
    ],
    safelist: [
        "bg-yellow-100",
        "text-sky-800",
        "border-sky-500",
        "bg-amber-400",
        "text-yellow-800",
        "rounded-lg",
        "p-4",
        "shadow",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
