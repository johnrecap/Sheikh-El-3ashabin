


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
                // ===== 🌿 HERBAL APOTHECARY THEME =====

                // Text Colors - Natural earth tones
                "text": "#2D3319",              // Deep olive for readability
                "success": "#4A7C23",           // Fresh herb green
                "danger": "#8B3A3A",            // Muted red (natural)
                "focus": "#7A6B4E",             // Warm wood focus
                "heading": "#1F2611",           // Almost black-green headings
                "paragraph": "#4A4A3A",         // Earthy gray-brown

                // Primary - Deep Forest Green (Main brand color)
                "primary": "#2D5016",           // Deep herbal green (CTAs, buttons)
                "primary-light": "#F7F5F0",     // Parchment/paper white
                "primary-slate": "#E8E4D9",     // Light natural beige

                // Secondary - Rich Wood Brown
                "secondary": "#5C4A32",         // Warm wood brown (header/footer)

                // Accent colors for authentic herbal feel
                "herbal-green": "#4A7C23",      // Fresh leaf green
                "herbal-cream": "#F5F2E8",      // Old paper cream
                "herbal-tan": "#C9B896",        // Natural twine/rope
                "herbal-brown": "#7A5C3E",      // Rich bark brown
                "herbal-dark": "#2A1F14",       // Dark wood
                "herbal-gold": "#B8860B",       // Golden honey/spice
                "herbal-sage": "#9CAF88",       // Soft sage green

                // Keep StoreKing utility colors (mapped to herbal theme)
                "storeKing-red": "#8B3A3A",     // Natural muted red
                "storeKing-pink": "#A67C52",    // Warm terracotta
                "storeKing-cyan": "#5F8A8B",    // Muted teal (herbs in water)
                "storeKing-gray": "#F5F2E8",    // Cream paper
                "storeKing-green": "#4A7C23",   // Fresh green
                "storeKing-slate": "#E8E4D9",   // Light beige
                "storeKing-yellow": "#B8860B",  // Golden/honey
                "storeKing-orange": "#C17F3C",  // Turmeric/spice orange
                "storeKing-purple": "#6B5B73",  // Dried lavender
                "storeKing-blue": "#4A6670",    // Muted blue-gray

                // Admin colors - keep original for clarity
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
                "indicate": "0px 2px 6px rgba(45, 80, 22, 0.46)",
                "filter": "0px 8px 16px rgba(23, 31, 70, 0.08)",
                "badge": "0px 4px 16px rgba(126, 133, 142, 0.16)",
                "sidebar": "15px 0px 25px 0px rgba(0, 0, 0, 0.08)",
                "sidebar-right": "15px 0px 25px 0px rgb(0 0 0 / 12%)",
                "db-sidebar-left": "0 0.125rem -0.375rem 0 rgb(161 172 184 / 12%)",
                "db-sidebar-right": "0 0.125rem 0.375rem 0 rgb(161 172 184 / 12%)",
                "pink": "0px 6px 15px rgba(166, 124, 82, 0.25)",
                "cyan": "0px 6px 15px 0px rgba(95, 138, 139, 0.25)",
                "green": "0px 6px 15px 0px rgba(45, 80, 22, 0.25)",
                "orange": "0px 6px 15px rgba(193, 127, 60, 0.25)",
                "purple": "0px 6px 15px rgba(107, 91, 115, 0.25)",
                "blue": "0px 6px 15px rgba(74, 102, 112, 0.25)",
                "sidebar-left": "-15px 0px 25px 0px rgb(0 0 0 / 12%)",
                "checkRound": "0 2px 4px 0 rgb(45 80 22 / 40%)",
                "cookies": "0px 15px 40px 0px rgba(73, 72, 72, 0.16)",
                "action": "0px 6px 10px rgba(45, 80, 22, 0.24)",
                "db-card": "0 2px 6px 0 rgb(67 89 113 / 12%)",
                "widget": "0px 4px 32px rgba(0, 0, 0, 0.06)",
                "cart": "0px 4px 12px 0px rgba(45, 80, 22, 0.30)",
                "btn-primary": "0px 8px 15px 0px rgba(45, 80, 22, 0.20)",
                "btn-secondary": "0px 4px 8px rgba(0, 0, 0, 0.04), 0px 0px 2px rgba(0, 0, 0, 0.06), 0px 0px 1px rgba(0, 0, 0, 0.04)",
            },
        },
    },
    plugins: [],
}
