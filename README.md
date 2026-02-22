# Yamama Themes & Plugins

Custom WordPress themes and plugins for Yamama store provisioning.

## Structure

```
themes/          → 16 custom WooCommerce themes
plugins/         → Shared plugins (shipping, payment gateways)
mu-plugins/      → Must-use plugins (auto-loaded)
```

## Themes

| Theme | Description |
|-------|-------------|
| mallati-theme | General store |
| al-thabihah-theme | Meat & poultry |
| stationary-theme | Stationery |
| elegance-theme | Elegant fashion |
| beauty-care-theme | Beauty & care |
| sweet-house-theme | Sweets & bakery |
| dark-theme | Dark UI store |
| My-kitchen-theme | Kitchen & food |
| yaamama-theme | Default Yamama |
| beauty-time-theme | Beauty salon |
| ahmadi-theme | General store |
| my-clinic-theme | Clinic & medical |
| my-car-theme | Auto & vehicles |
| nafhat-theme | Perfumes & oud |
| techno-souq-theme | Electronics |
| khutaa-theme | Fashion & shoes |

## Usage on Store Host

```bash
git clone https://github.com/mujtaba37353/yaamama-themes.git /var/www/templates/yaamama-themes
```

### Update themes

```bash
cd /var/www/templates/yaamama-themes && git pull origin main
```
