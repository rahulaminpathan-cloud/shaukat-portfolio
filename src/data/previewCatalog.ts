import {
  CustomerTestimonial,
  HeroSlide,
  JournalArticle,
  StoreCategory,
  StoreProduct,
} from '../types/store';

export const HERO_SLIDES: HeroSlide[] = [
  {
    id: 'slide-living',
    kicker: 'Dhaka Living Edit · 2026',
    headline: 'Warm Teak & Handcrafted Home Essentials',
    subcopy:
      'Architectural shelving, brushed brass lighting, and artisanal stoneware crafted for modern Bangladeshi homes. Cash on Delivery nationwide.',
    ctaLabel: 'Explore Home & Living',
    targetCategory: 'home-furniture',
    image: '/src/assets/images/hero_living_showcase_1795365630561.jpg',
  },
  {
    id: 'slide-lifestyle',
    kicker: 'Artisan & Botanical Capsule',
    headline: 'Handloom Textiles Meets Pure Botanical Care',
    subcopy:
      'Small-batch botanical serums, handwoven Tangail cottons, and solid acacia pantry ware curated with verified local craftsmanship.',
    ctaLabel: 'Shop Lifestyle Goods',
    targetCategory: 'skincare-beauty',
    image: '/src/assets/images/hero_artisan_lifestyle_1795365630232.jpg',
  },
];

export const STORE_CATEGORIES: StoreCategory[] = [
  {
    slug: 'home-furniture',
    name: 'Home & Shelving',
    itemCount: 38,
    subtitle: 'Teak racks, brass lamps & living accents',
    image: '/src/assets/images/prod_teak_bookshelf_1795365629673.jpg',
    subcategories: [
      { slug: 'home-furniture', name: 'Teak Bookshelves', itemCount: 14 },
      { slug: 'home-furniture', name: 'Coffee & Side Tables', itemCount: 11 },
      { slug: 'home-furniture', name: 'Brass Table Lamps', itemCount: 13 },
    ],
  },
  {
    slug: 'kitchen-dining',
    name: 'Kitchen & Dining',
    itemCount: 54,
    subtitle: 'Stoneware tea sets & acacia organizers',
    image: '/src/assets/images/prod_ceramic_tea_set_1795365630085.jpg',
    subcategories: [
      { slug: 'kitchen-dining', name: 'Stoneware Tea Sets', itemCount: 22 },
      { slug: 'kitchen-dining', name: 'Spice & Pantry Organizers', itemCount: 19 },
      { slug: 'kitchen-dining', name: 'Serving Trays', itemCount: 13 },
    ],
  },
  {
    slug: 'skincare-beauty',
    name: 'Botanical Skincare',
    itemCount: 29,
    subtitle: 'Cold-pressed serums & daily hydration',
    image: '/src/assets/images/prod_amber_skincare_1795365629913.jpg',
    subcategories: [
      { slug: 'skincare-beauty', name: 'Facial Serums & Oils', itemCount: 16 },
      { slug: 'skincare-beauty', name: 'Barrier Moisturizers', itemCount: 13 },
    ],
  },
  {
    slug: 'handloom-apparel',
    name: 'Handloom & Craft',
    itemCount: 42,
    subtitle: 'Jamdani shawls & woven cane baskets',
    image: '/src/assets/images/prod_jamdani_textile_1795365629456.jpg',
    subcategories: [
      { slug: 'handloom-apparel', name: 'Jamdani Cotton Shawls', itemCount: 24 },
      { slug: 'handloom-apparel', name: 'Sylheti Cane Baskets', itemCount: 18 },
    ],
  },
];

export const PREVIEW_PRODUCTS: StoreProduct[] = [
  {
    id: 'tm-101',
    sku: 'TMB-FUR-014',
    name: 'Baridhara 4-Tier Teak & Steel Bookshelf',
    category: 'Home & Shelving',
    categorySlug: 'home-furniture',
    origin: 'Dhaka Workshop',
    regularPrice: 12900,
    salePrice: 10450,
    rating: 4.9,
    reviewCount: 47,
    stockStatus: 'In Stock',
    stockCount: 14,
    image: '/src/assets/images/prod_teak_bookshelf_1795365629673.jpg',
    gallery: [
      '/src/assets/images/prod_teak_bookshelf_1795365629673.jpg',
      '/src/assets/images/hero_living_showcase_1795365630561.jpg',
    ],
    collections: ['best-selling', 'featured', 'discount'],
    shortDescription:
      'Seasoned Chittagong teak planks paired with powder-coated matte charcoal steel frame.',
    description:
      'Built for apartments and home offices across Dhaka, the Baridhara 4-Tier Bookshelf pairs kiln-dried teak shelves with cross-braced matte steel uprights. Easy 15-minute assembly with included hex key.',
    specs: [
      { label: 'Dimensions', value: '148 cm H × 76 cm W × 32 cm D' },
      { label: 'Timber', value: 'Seasoned Chittagong Teak' },
      { label: 'Frame', value: 'Powder-coated 1.6mm Steel' },
      { label: 'Load Capacity', value: '28 kg per shelf' },
    ],
    variant: {
      label: 'Wood Finish',
      options: ['Natural Honey Teak', 'Warm Walnut Stain'],
    },
    reviews: [
      {
        id: 'r1',
        author: 'Tanvir Ahmed',
        district: 'Banani, Dhaka',
        rating: 5,
        date: 'Oct 18, 2026',
        comment: 'Zero wobble and genuine solid teak grain. Delivered assembled inside Dhaka within 24 hours.',
        verified: true,
      },
    ],
  },
  {
    id: 'tm-102',
    sku: 'TMB-KIT-029',
    name: 'Sylhet Speckled Stoneware Tea Service',
    category: 'Kitchen & Dining',
    categorySlug: 'kitchen-dining',
    origin: 'Artisan Kiln',
    regularPrice: 2850,
    salePrice: 2390,
    rating: 4.8,
    reviewCount: 63,
    stockStatus: 'In Stock',
    stockCount: 22,
    image: '/src/assets/images/prod_ceramic_tea_set_1795365630085.jpg',
    gallery: [
      '/src/assets/images/prod_ceramic_tea_set_1795365630085.jpg',
      '/src/assets/images/hero_artisan_lifestyle_1795365630232.jpg',
    ],
    collections: ['best-selling', 'latest', 'discount'],
    shortDescription:
      'Lead-free high-fired stoneware teapot (650ml) with two matching cups and oak serving tray.',
    description:
      'Hand-thrown and speckle-glazed at 1240°C for superior heat retention. Designed for slow afternoon cha sessions and effortless gifting.',
    specs: [
      { label: 'Teapot Volume', value: '650 ml' },
      { label: 'Includes', value: '1 Teapot, 2 Cups, 1 Oak Tray' },
      { label: 'Care', value: 'Dishwasher & microwave safe (ceramic)' },
    ],
    variant: {
      label: 'Glaze Tone',
      options: ['Oatmeal Speckle', 'Warm Sand Matte'],
    },
    reviews: [
      {
        id: 'r2',
        author: 'Nusrat Jahan',
        district: 'Uttara, Dhaka',
        rating: 5,
        date: 'Nov 02, 2026',
        comment: 'Pours cleanly without dripping and the oak tray feels substantial.',
        verified: true,
      },
    ],
  },
  {
    id: 'tm-103',
    sku: 'TMB-BEA-008',
    name: 'Botanical Amber Facial Serum & Cream Duo',
    category: 'Botanical Skincare',
    categorySlug: 'skincare-beauty',
    origin: 'Small Batch',
    regularPrice: 2400,
    salePrice: 1950,
    rating: 4.9,
    reviewCount: 81,
    stockStatus: 'In Stock',
    stockCount: 31,
    image: '/src/assets/images/prod_amber_skincare_1795365629913.jpg',
    gallery: [
      '/src/assets/images/prod_amber_skincare_1795365629913.jpg',
      '/src/assets/images/hero_artisan_lifestyle_1795365630232.jpg',
    ],
    collections: ['best-selling', 'featured', 'discount'],
    shortDescription:
      'Cold-pressed rosehip & neroli brightening serum (30ml) paired with barrier cream (50ml).',
    description:
      'Formulated for humid tropical climates without heavy silicones or synthetic fragrance. Rapidly absorbs to restore skin radiance overnight.',
    specs: [
      { label: 'Serum Size', value: '30 ml UV-amber glass' },
      { label: 'Cream Size', value: '50 ml frosted glass jar' },
      { label: 'Skin Type', value: 'All skin types, non-comedogenic' },
    ],
    variant: {
      label: 'Edition',
      options: ['Complete Duo (30ml + 50ml)', 'Serum Only (30ml)'],
    },
    reviews: [
      {
        id: 'r3',
        author: 'Farhana Karim',
        district: 'Nasirabad, Chattogram',
        rating: 5,
        date: 'Nov 08, 2026',
        comment: 'Lightweight even in Dhaka humidity. Authentic packaging and sealed batch code.',
        verified: true,
      },
    ],
  },
  {
    id: 'tm-104',
    sku: 'TMB-TEX-041',
    name: 'Sonargaon Handloom Jamdani Cotton Shawl',
    category: 'Handloom & Craft',
    categorySlug: 'handloom-apparel',
    origin: 'Narayanganj Loom',
    regularPrice: 3600,
    rating: 5.0,
    reviewCount: 39,
    stockStatus: 'Low Stock',
    stockCount: 5,
    image: '/src/assets/images/prod_jamdani_textile_1795365629456.jpg',
    gallery: ['/src/assets/images/prod_jamdani_textile_1795365629456.jpg'],
    collections: ['featured', 'latest'],
    shortDescription:
      '80-count breathable handwoven cotton stole with traditional forest-emerald floral motifs.',
    description:
      'Woven by master artisans in Sonargaon using unbleached ivory cotton warp and botanical forest-green inlay threads with hand-knotted tassels.',
    specs: [
      { label: 'Material', value: '100% Handspun 80-Count Cotton' },
      { label: 'Dimensions', value: '220 cm × 90 cm' },
      { label: 'Weave Time', value: '6 days per piece' },
    ],
    variant: {
      label: 'Border Motif',
      options: ['Forest Emerald Floral', 'Indigo Paisley'],
    },
    reviews: [
      {
        id: 'r4',
        author: 'Maliha Chowdhury',
        district: 'Dhanmondi, Dhaka',
        rating: 5,
        date: 'Oct 29, 2026',
        comment: 'The drape and crisp cotton feel are extraordinary. Worth every taka.',
        verified: true,
      },
    ],
  },
  {
    id: 'tm-105',
    sku: 'TMB-LGT-019',
    name: 'Brushed Antique Brass & Linen Table Lamp',
    category: 'Home & Shelving',
    categorySlug: 'home-furniture',
    origin: 'Studio Craft',
    regularPrice: 4800,
    salePrice: 4100,
    rating: 4.8,
    reviewCount: 28,
    stockStatus: 'In Stock',
    stockCount: 11,
    image: '/src/assets/images/prod_brass_table_lamp_1795365629582.jpg',
    gallery: ['/src/assets/images/prod_brass_table_lamp_1795365629582.jpg'],
    collections: ['featured', 'latest', 'discount'],
    shortDescription:
      'Solid turned brass stem with textured oatmeal linen drum shade and braided inline switch.',
    description:
      'Casts a warm 2700K ambient glow ideal for bedside tables, reading corners, and console credenzas. Includes energy-efficient E27 LED bulb.',
    specs: [
      { label: 'Height', value: '46 cm overall' },
      { label: 'Base Finish', value: 'Brushed Antique Brass' },
      { label: 'Socket', value: 'Standard E27 (220V Bangladesh plug)' },
    ],
    variant: {
      label: 'Shade Fabric',
      options: ['Oatmeal Raw Linen', 'Warm Ivory Cotton'],
    },
    reviews: [],
  },
  {
    id: 'tm-106',
    sku: 'TMB-KIT-052',
    name: 'Acacia Two-Tier Pantry & Spice Jar Set (16-Pc)',
    category: 'Kitchen & Dining',
    categorySlug: 'kitchen-dining',
    origin: 'Home Pantry',
    regularPrice: 3950,
    salePrice: 3250,
    rating: 4.9,
    reviewCount: 74,
    stockStatus: 'In Stock',
    stockCount: 19,
    image: '/src/assets/images/prod_acacia_organizer_1795365629668.jpg',
    gallery: ['/src/assets/images/prod_acacia_organizer_1795365629668.jpg'],
    collections: ['best-selling', 'latest', 'discount'],
    shortDescription:
      'Solid acacia wood countertop stand with 16 airtight borosilicate jars and minimalist labels.',
    description:
      'Keep whole spices, lentils, and daily condiments organized and moisture-sealed. Airtight silicone-gasket bamboo lids keep contents fresh in humid kitchens.',
    specs: [
      { label: 'Rack Width', value: '44 cm × 18 cm × 29 cm' },
      { label: 'Jars Included', value: '10 Spice (120ml) + 6 Pantry (450ml)' },
      { label: 'Glass Type', value: 'Thick Borosilicate Glass' },
    ],
    variant: {
      label: 'Set Configuration',
      options: ['16-Jar Full Set + Rack', '10-Jar Upper Set'],
    },
    reviews: [],
  },
  {
    id: 'tm-107',
    sku: 'TMB-FUR-033',
    name: 'Gulshan Low-Profile Teak Lounge Coffee Table',
    category: 'Home & Shelving',
    categorySlug: 'home-furniture',
    origin: 'Dhaka Workshop',
    regularPrice: 15800,
    rating: 4.7,
    reviewCount: 19,
    stockStatus: 'In Stock',
    stockCount: 6,
    image: '/src/assets/images/hero_living_showcase_1795365630561.jpg',
    gallery: ['/src/assets/images/hero_living_showcase_1795365630561.jpg'],
    collections: ['latest'],
    shortDescription:
      'Architectural slab-leg coffee table finished in natural matte oil for enduring grain protection.',
    description:
      'Crafted from wide-plank Burmese-grade teak with softly chamfered edges. Designed to anchor contemporary living spaces alongside woven jute rugs.',
    specs: [
      { label: 'Dimensions', value: '110 cm L × 60 cm W × 38 cm H' },
      { label: 'Finish', value: 'Hand-rubbed Matte Beeswax Oil' },
    ],
    reviews: [],
  },
  {
    id: 'tm-108',
    sku: 'TMB-TEX-064',
    name: 'Artisan Cane Basket & Handloom Linen Bundle',
    category: 'Handloom & Craft',
    categorySlug: 'handloom-apparel',
    origin: 'Sylhet & Tangail',
    regularPrice: 2150,
    salePrice: 1790,
    rating: 4.8,
    reviewCount: 41,
    stockStatus: 'In Stock',
    stockCount: 25,
    image: '/src/assets/images/prod_jamdani_textile_1795365629456.jpg',
    gallery: ['/src/assets/images/prod_jamdani_textile_1795365629456.jpg'],
    collections: ['discount', 'latest'],
    shortDescription:
      'Hand-plaited Sylheti cane market basket paired with two unbleached handloom table runners.',
    description:
      'Ideal for dining presentation, gift hampers, or bedside storage. Durable natural cane cured for indoor longevity.',
    specs: [
      { label: 'Basket Size', value: '28 cm diameter × 16 cm height' },
      { label: 'Runners', value: '2 pieces · 160 cm × 36 cm' },
    ],
    reviews: [],
  },
];

export const TESTIMONIALS: CustomerTestimonial[] = [
  {
    id: 't1',
    name: 'Sadia Rahman',
    location: 'Gulshan 2, Dhaka',
    purchasedItem: 'Baridhara 4-Tier Teak Bookshelf',
    rating: 5,
    quote:
      'Ordered on Tuesday morning and received white-glove delivery Wednesday afternoon. The teak finish matches our living room effortlessly.',
  },
  {
    id: 't2',
    name: 'Imran Hossain',
    location: 'Panchlaish, Chattogram',
    purchasedItem: 'Acacia 16-Pc Spice Organizer',
    rating: 5,
    quote:
      'Every glass jar arrived double-boxed with zero breakage. Paying via bKash after inspecting the parcel made the experience stress-free.',
  },
  {
    id: 't3',
    name: 'Tasnim Kabir',
    location: 'Zindabazar, Sylhet',
    purchasedItem: 'Sonargaon Jamdani Cotton Shawl',
    rating: 5,
    quote:
      'Authentic Bangladeshi handloom weaving without inflated boutique markups. Truly thoughtful curation.',
  },
];

export const JOURNAL_POSTS: JournalArticle[] = [
  {
    id: 'j1',
    category: 'Interior Guide',
    date: 'Nov 12, 2026',
    readTime: '4 min read',
    title: 'Choosing Seasoned Teak Shelving for Humid Dhaka Apartments',
    excerpt:
      'How kiln-dried timber and breathable matte oil finishes prevent warping across monsoon seasons.',
    image: '/src/assets/images/hero_living_showcase_1795365630561.jpg',
  },
  {
    id: 'j2',
    category: 'Craftsmanship',
    date: 'Nov 04, 2026',
    readTime: '5 min read',
    title: 'Inside Sonargaon Looms: Preserving 80-Count Cotton Jamdani',
    excerpt:
      'Meet the weaving families behind our hand-knotted emerald cotton stoles and natural dye batches.',
    image: '/src/assets/images/prod_jamdani_textile_1795365629456.jpg',
  },
  {
    id: 'j3',
    category: 'Kitchen Rituals',
    date: 'Oct 27, 2026',
    readTime: '3 min read',
    title: 'Organizing Whole Bangladeshi Spices with Airtight Borosilicate Glass',
    excerpt:
      'Practical pantry layout tips to keep cardamom, cumin, and bay leaves aromatic year-round.',
    image: '/src/assets/images/prod_acacia_organizer_1795365629668.jpg',
  },
];
