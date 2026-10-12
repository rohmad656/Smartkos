# Design System.md

## 1. Color Palette
- **--paper**: #FFF9EB (kertas kos, background utama)
- **--ink**: #1B1E1A (teks utama, kontras tinggi)
- **--teak**: #3D2C17 (pintu kayu, elemen struktural)
- **--teal**: #0E7C6B (ubin tegel, aksen utama)
- **--mustard**: #E8B44A (ember, aksen segmen, CTA)
- **--line**: #D9D6CF (garis, border ringan)
- **--muted**: #6B6760 (teks sekunder, label)

## 2. Typography
- **Display**: Fraunces (serif), weights 400-900, size 14px-48px, line-height 1.2-1.6
- **Body**: DM Sans (sans-serif), weights 400-500, size 14px-16px, line-height 1.5-1.6
- **Headings**: Fraunces, weight 700-900, tracking tighter

## 3. Spacing & Layout
- **Base unit**: 4px
- **Margins/Padding**: 4px, 8px, 12px, 16px, 16px (container), 24px (section)
- **Grid**: 12-column, 24px gutter, max-width 1160px

## 4. Components
### Buttons
- **Primary**: background --teak, color #FFF, border 2px solid --ink
- **Secondary**: background transparent, border 2px solid --ink
- **Ghost**: transparent, border none, text --ink

### Cards
- **Kos Card**: background #FFF, border 2px solid --ink, border-radius 20px, box-shadow 4px 4px 0 var(--ink)
- **Tall Card**: min-height 420px, image height 210px
- **Stagger**: nth-child(3n+2) transform translateY(10px)

### Forms
- **Input**: border 1.5px solid --line, border-radius 12px, padding 11px 12px, min-width 160px
- **Select**: same as input, min-width 140px

### Alerts
- **Success**: background #C9E6E0, border 1.5px solid var(--teal)
- **Error**: background #F8D7DA, border 1.5px solid var(--muted)
- **Info**: background #E8F0EC, border 1.5px solid var(--teal)

### Nav
- **Nav Bar**: sticky top, border-bottom 2px solid --ink
- **Logo**: --teak background, --mustard accent dot

## 5. Responsive Rules
- **Desktop (>1024px)**: 3-column grid (kosan), hero split (50/50)
- **Tablet (768-1023px)**: 2-column grid, hero full width
- **Mobile (<768px)**: 1-column, hamburger menu, hero stacked

## 6. UI Conventions
- **Left-aligned text** for body content
- **Centered** for hero headlines
- **No ALL-CAPS** except brand name
- **No single word italic/bold** in headlines
- **No ALL-CAPS labels**
- **No decorative borders** without functional purpose
- **Motion**: Only for pin click, chip toggle, and loading states. No auto-animation.

## 7. Accessibility
- Focus outline: 3px solid var(--teal)
- Contrast ratio: 4.5:1 minimum for text/background
- ARIA labels for icons and interactive elements
- Reduced motion: respect `prefers-reduced-motion` media query

## 8. Indonesian Localization
- Currency: Rupiah (Rp), formatted with comma ribuan (e.g., Rp1.250.000)
- Dates: DD/MM/YYYY format
- Time: 24-hour format (HH:MM)
- Voice: conversational, active, direct, no filler words

## 9. Asset Guidelines
- Images: real photos from tenants, not stock
- Icons: minimalist line icons, consistent stroke width
- Fonts: Instrument Sans (UI), Fraunces (display), DM Sans (body)