


/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            backgroundImage: {
                stock: "url('/images/stock-badge.png')"
            },
            fontFamily: {
                "client": ["var(--client-font)"],
                "admin": ["var(--admin-font)"],
                "icon": ["var(--icon-font)"],
                "awesome": ["'Font Awesome 6 Free'"],
            },
            screens: {
                'mobile': { 'min': '0px', 'max': '640px' },
                'tablet': { 'min': '641px', 'max': '767px' },
                'laptop': { 'min': '768px', 'max': '1024px' },
                'desktop': { 'min': '1025px', 'max': '1280px' },
                'monitor': { 'min': '1281px', 'max': '1536px' },

                'max-sm': { 'min': '0px', 'max': '640px' },
                'max-md': { 'min': '0px', 'max': '767px' },
                'max-lg': { 'min': '0px', 'max': '1024px' },
                'max-xl': { 'min': '0px', 'max': '1280px' },

                'xh': { 'min': '0px', 'max': '767px' },
            },
            colors: {
                "text": "#475569",          // Slate 600 - Sophisticated text
                "success": "#10B981",       // Emerald 500 - Modern Green
                "danger": "#EF4444",        // Red 500 - Modern Red
                "focus": "#CFB071",         // Gold focus
                "heading": "#0F172A",       // Slate 900 - Dark elegant headings
                "paragraph": "#334155",     // Slate 700 - Readable paragraph
                "primary": "#CFB071",       // MUTED GLAMOUR GOLD
                "primary-light": "#F9F6F0", // Creamy/Off-white
                "primary-slate": "#F1F5F9", // Slate 100
                "secondary": "#020617",     // Rich Black/Night Blue
                "storeKing-red": "#FF6262",
                "storeKing-pink": "#FD0063",
                "storeKing-cyan": "#1CB7CD",
                "storeKing-gray": "#EFF0F6",
                "storeKing-green": "#01BE5F",
                "storeKing-slate": "#D9DBE9",
                "storeKing-yellow": "#FFBC1F",
                "storeKing-orange": "#F23E14",
                "storeKing-purple": "#9353DE",
                "storeKing-blue": "#0072F4",
                admin: {
                    red: "#FB4E4E",
                    sky: "#007FE3",
                    pink: "#FD0063",
                    blue: "#426EFF",
                    green: "#2AC769",
                    orange: "#F23E14",
                    yellow: "#F6A609",
                    purple: "#6A45FE",
                }
            },
            minWidth: {
                '3xl': '48rem',
            },
            lineHeight: {
                '11': "2.75rem",
                '12': "3rem",
            },
            zIndex: {
                "60": "60",
                "70": "70",
                "80": "80",
            },
            boxShadow: {
                "xs": '0px 6px 32px rgba(0, 0, 0, 0.04)',
                "xst": "0px 4px 40px rgba(23, 31, 70, 0.16)",
                "card": "0px 0px 10px rgba(0, 0, 0, 0.04)",
                "hover": "0px 8px 40px rgba(23, 31, 70, 0.08)",
                "paper": "0px 15px 40px rgba(73, 72, 72, 0.1)",
                "b-paper": "0px 15px 40px rgba(73, 72, 72, 0.1)",
                "t-paper": "0px -15px 40px rgba(73, 72, 72, 0.1)",
                "indicate": "0px 2px 6px rgba(242, 62, 20, 0.46)",
                "filter": "0px 8px 16px rgba(23, 31, 70, 0.08)",
                "badge": "0px 4px 16px rgba(126, 133, 142, 0.16)",
                "sidebar": "15px 0px 25px 0px rgba(0, 0, 0, 0.08)",
                "sidebar-right": "15px 0px 25px 0px rgb(0 0 0 / 12%)",
                "db-sidebar-left": "0 0.125rem -0.375rem 0 rgb(161 172 184 / 12%)",
                "db-sidebar-right": "0 0.125rem 0.375rem 0 rgb(161 172 184 / 12%)",
                "pink": "0px 6px 15px rgba(253, 0, 99, 0.25)",
                "cyan": "0px 6px 15px 0px rgba(37, 183, 204, 0.25)",
                "green": "0px 6px 15px 0px rgba(0, 162, 81, 0.25)",
                "orange": "0px 6px 15px rgba(242, 62, 20, 0.25)",
                "purple": "0px 6px 15px rgba(147, 83, 222, 0.25)",
                "blue": "0px 6px 15px rgba(0, 114, 244, 0.25)",
                "sidebar-left": "-15px 0px 25px 0px rgb(0 0 0 / 12%)",
                "checkRound": "0 2px 4px 0 rgb(105 108 255 / 40%)",
                "cookies": "0px 15px 40px 0px rgba(73, 72, 72, 0.16)",
                "action": "0px 6px 10px rgba(242, 62, 20, 0.24)",
                "db-card": "0 2px 6px 0 rgb(67 89 113 / 12%)",
                "widget": "0px 4px 32px rgba(0, 0, 0, 0.06)",
                "cart": "0px 6px 10px rgba(242, 62, 20, 0.34)",
                "btn-primary": "0px 8px 15px 0px rgba(1, 190, 95, 0.20)",
                "btn-secondary": "0px 4px 8px rgba(0, 0, 0, 0.04), 0px 0px 2px rgba(0, 0, 0, 0.06), 0px 0px 1px rgba(0, 0, 0, 0.04)",
                "cart": "0px 4px 12px 0px rgba(0, 162, 81, 0.30)"
            },
        },
    },
    plugins: [],
}
