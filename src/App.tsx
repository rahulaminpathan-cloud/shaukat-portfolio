import { useMemo, useState } from 'react';
import {
  ArrowRight,
  ChevronLeft,
  ChevronRight,
  Check,
  Facebook,
  Instagram,
  MapPin,
  Mail,
  MessageCircle,
  Monitor,
  Phone,
  RotateCcw,
  ShieldCheck,
  Smartphone,
  Star,
  Truck,
  Wallet,
  Youtube,
} from 'lucide-react';
import { Header } from './components/Header';
import { ProductCard } from './components/ProductCard';
import { SmartImage } from './components/SmartImage';
import {
  AccountModal,
  CartDrawer,
  ProductDetailModal,
  WishlistDrawer,
} from './components/ModalsAndDrawers';
import {
  HERO_SLIDES,
  JOURNAL_POSTS,
  PREVIEW_PRODUCTS,
  STORE_CATEGORIES,
  TESTIMONIALS,
} from './data/previewCatalog';
import { CartItem, ProductCollectionTag, StoreProduct } from './types/store';

type CollectionTab = 'all' | ProductCollectionTag;
type SortOption = 'featured' | 'price-asc' | 'price-desc' | 'rating';

export default function App() {
  const isEmbeddedMobileFrame =
    typeof window !== 'undefined' &&
    new URLSearchParams(window.location.search).get('preview') === 'mobile';
  const [viewportMode, setViewportMode] = useState<'desktop' | 'mobile'>('desktop');

  /* Hero Slider State */
  const [slideIdx, setSlideIdx] = useState(0);

  /* Catalog Filter & Search State */
  const [searchQuery, setSearchQuery] = useState('');
  const [activeCollection, setActiveCollection] = useState<CollectionTab>('best-selling');
  const [activeCategory, setActiveCategory] = useState<string>('all');
  const [sortBy, setSortBy] = useState<SortOption>('featured');
  const [spotlightCategory, setSpotlightCategory] = useState<string>('home-furniture');

  /* Commerce Interaction State */
  const [cart, setCart] = useState<CartItem[]>([
    {
      product: PREVIEW_PRODUCTS[1],
      quantity: 1,
      selectedVariant: PREVIEW_PRODUCTS[1].variant?.options[0],
    },
  ]);
  const [wishlistIds, setWishlistIds] = useState<string[]>(['tm-104']);
  const [cartOpen, setCartOpen] = useState(false);
  const [wishlistOpen, setWishlistOpen] = useState(false);
  const [accountOpen, setAccountOpen] = useState(false);
  const [detailProduct, setDetailProduct] = useState<StoreProduct | null>(null);
  const [toastMessage, setToastMessage] = useState<string | null>(null);

  /* Newsletter State */
  const [newsletterEmail, setNewsletterEmail] = useState('');
  const [newsletterSubscribed, setNewsletterSubscribed] = useState(false);

  const showToast = (msg: string) => {
    setToastMessage(msg);
    setTimeout(() => {
      setToastMessage((prev) => (prev === msg ? null : prev));
    }, 2600);
  };

  /* Cart Handlers */
  const handleAddToCart = (product: StoreProduct, qty = 1, variant?: string) => {
    const resolvedVariant = variant ?? product.variant?.options[0];
    setCart((prev) => {
      const existing = prev.find(
        (item) => item.product.id === product.id && item.selectedVariant === resolvedVariant
      );
      if (existing) {
        return prev.map((item) =>
          item.product.id === product.id && item.selectedVariant === resolvedVariant
            ? { ...item, quantity: item.quantity + qty }
            : item
        );
      }
      return [...prev, { product, quantity: qty, selectedVariant: resolvedVariant }];
    });
    showToast(`Added "${product.name}" to bag`);
  };

  const handleBuyNow = (product: StoreProduct, qty = 1, variant?: string) => {
    handleAddToCart(product, qty, variant);
    setCartOpen(true);
  };

  const handleUpdateQty = (productId: string, variant: string | undefined, delta: number) => {
    setCart((prev) =>
      prev
        .map((item) =>
          item.product.id === productId && item.selectedVariant === variant
            ? { ...item, quantity: item.quantity + delta }
            : item
        )
        .filter((item) => item.quantity > 0)
    );
  };

  const handleRemoveCartItem = (productId: string, variant: string | undefined) => {
    setCart((prev) =>
      prev.filter((item) => !(item.product.id === productId && item.selectedVariant === variant))
    );
  };

  const handleToggleWishlist = (productId: string) => {
    setWishlistIds((prev) => {
      const exists = prev.includes(productId);
      const next = exists ? prev.filter((id) => id !== productId) : [...prev, productId];
      showToast(exists ? 'Removed from wishlist' : 'Saved to wishlist');
      return next;
    });
  };

  const navigateToSection = (sectionId: string, categorySlug?: string) => {
    if (categorySlug) {
      setActiveCategory(categorySlug);
      setActiveCollection('all');
    }
    const el = document.getElementById(sectionId);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  /* Filtered Products */
  const quickSearchResults = useMemo(() => {
    const q = searchQuery.trim().toLowerCase();
    if (!q) return [];
    return PREVIEW_PRODUCTS.filter(
      (p) =>
        p.name.toLowerCase().includes(q) ||
        p.category.toLowerCase().includes(q) ||
        p.origin.toLowerCase().includes(q)
    );
  }, [searchQuery]);

  const filteredCatalog = useMemo(() => {
    const q = searchQuery.trim().toLowerCase();
    let list = PREVIEW_PRODUCTS.filter((p) => {
      const matchesCollection =
        activeCollection === 'all' || p.collections.includes(activeCollection);
      const matchesCategory = activeCategory === 'all' || p.categorySlug === activeCategory;
      const matchesSearch =
        !q ||
        p.name.toLowerCase().includes(q) ||
        p.category.toLowerCase().includes(q) ||
        p.shortDescription.toLowerCase().includes(q);
      return matchesCollection && matchesCategory && matchesSearch;
    });

    if (sortBy === 'price-asc') {
      list = [...list].sort(
        (a, b) => (a.salePrice ?? a.regularPrice) - (b.salePrice ?? b.regularPrice)
      );
    } else if (sortBy === 'price-desc') {
      list = [...list].sort(
        (a, b) => (b.salePrice ?? b.regularPrice) - (a.salePrice ?? a.regularPrice)
      );
    } else if (sortBy === 'rating') {
      list = [...list].sort((a, b) => b.rating - a.rating);
    }
    return list;
  }, [activeCollection, activeCategory, searchQuery, sortBy]);

  const spotlightProducts = useMemo(
    () => PREVIEW_PRODUCTS.filter((p) => p.categorySlug === spotlightCategory),
    [spotlightCategory]
  );

  const wishlistedProducts = useMemo(
    () => PREVIEW_PRODUCTS.filter((p) => wishlistIds.includes(p.id)),
    [wishlistIds]
  );

  const totalCartItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartSubtotal = cart.reduce(
    (sum, item) => sum + (item.product.salePrice ?? item.product.regularPrice) * item.quantity,
    0
  );
  const currentSlide = HERO_SLIDES[slideIdx];

  return (
    <div id="top" className="min-h-screen flex flex-col bg-[#FAF9F6] text-[#1C1917]">
      {/* Header & Navigation */}
      <Header
        searchQuery={searchQuery}
        onSearchChange={setSearchQuery}
        cartCount={totalCartItems}
        cartSubtotal={cartSubtotal}
        wishlistCount={wishlistIds.length}
        onOpenCart={() => setCartOpen(true)}
        onOpenWishlist={() => setWishlistOpen(true)}
        onOpenAccount={() => setAccountOpen(true)}
        onNavigateSection={navigateToSection}
        quickSearchResults={quickSearchResults}
        onSelectProduct={(p) => setDetailProduct(p)}
      />

      <main className="flex-1">
        {/* 1. Responsive Hero Slider */}
        <section aria-label="Featured seasonal showcase" className="relative bg-[#F3F1EC] border-b border-[#E5E2DC]">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-stretch">
              {/* Left Copy Column */}
              <div className="lg:col-span-5 flex flex-col justify-between bg-white border border-[#E5E2DC] rounded-2xl p-6 sm:p-9">
                <div>
                  <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                    {currentSlide.kicker}
                  </p>
                  <h1 className="font-display text-3xl sm:text-4xl lg:text-[46px] font-semibold text-[#1C1917] leading-[1.12] mt-3 text-balance">
                    {currentSlide.headline}
                  </h1>
                  <p className="mt-4 text-[15px] text-[#57534E] leading-relaxed max-w-md">
                    {currentSlide.subcopy}
                  </p>
                </div>

                <div className="mt-8 pt-6 border-t border-[#F3F1EC] flex flex-wrap items-center justify-between gap-4">
                  <button
                    type="button"
                    onClick={() => navigateToSection('catalog', currentSlide.targetCategory)}
                    className="inline-flex items-center gap-2 px-5 py-3 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
                  >
                    {currentSlide.ctaLabel}
                    <ArrowRight className="w-4 h-4" />
                  </button>

                  <div className="flex items-center gap-2">
                    <button
                      type="button"
                      onClick={() =>
                        setSlideIdx((prev) => (prev - 1 + HERO_SLIDES.length) % HERO_SLIDES.length)
                      }
                      aria-label="Previous hero slide"
                      className="w-10 h-10 rounded-lg border border-[#E5E2DC] bg-[#FAF9F6] hover:bg-[#EFECE5] flex items-center justify-center transition-colors cursor-pointer"
                    >
                      <ChevronLeft className="w-4 h-4" />
                    </button>
                    <button
                      type="button"
                      onClick={() => setSlideIdx((prev) => (prev + 1) % HERO_SLIDES.length)}
                      aria-label="Next hero slide"
                      className="w-10 h-10 rounded-lg border border-[#E5E2DC] bg-[#FAF9F6] hover:bg-[#EFECE5] flex items-center justify-center transition-colors cursor-pointer"
                    >
                      <ChevronRight className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              </div>

              {/* Right Campaign Image */}
              <div className="lg:col-span-7 relative rounded-2xl overflow-hidden border border-[#E5E2DC] min-h-[280px] sm:min-h-[380px] lg:min-h-[440px]">
                <SmartImage
                  src={currentSlide.image}
                  alt={currentSlide.headline}
                  className="w-full h-full object-cover"
                  eager
                />
                <div className="absolute inset-x-0 bottom-0 p-5 sm:p-6 bg-gradient-to-t from-black/75 via-black/30 to-transparent flex items-end justify-between gap-4">
                  <div className="text-white">
                    <p className="text-xs font-medium text-white/85">
                      Phase 2 UI Visual Preview <span aria-hidden="true">·</span> Live WooCommerce binding in Phase 4
                    </p>
                  </div>
                  <div className="flex items-center gap-1.5">
                    {HERO_SLIDES.map((s, idx) => (
                      <button
                        key={s.id}
                        type="button"
                        onClick={() => setSlideIdx(idx)}
                        aria-label={`Show slide ${idx + 1}`}
                        className={`h-1.5 rounded-full transition-all cursor-pointer ${
                          slideIdx === idx ? 'w-7 bg-white' : 'w-2.5 bg-white/50 hover:bg-white/80'
                        }`}
                      />
                    ))}
                  </div>
                </div>
              </div>
            </div>

            {/* 2. Promotional Split Banners */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
              <div className="relative rounded-xl overflow-hidden border border-[#E5E2DC] bg-white grid grid-cols-1 sm:grid-cols-12 items-stretch">
                <div className="sm:col-span-7 p-6 flex flex-col justify-between">
                  <div>
                    <p className="text-xs font-semibold text-[#0F4C3A] uppercase tracking-wider">
                      Kitchen &amp; Pantry
                    </p>
                    <h2 className="font-display text-2xl font-semibold text-[#1C1917] mt-1">
                      Stoneware &amp; Acacia Organizers
                    </h2>
                    <p className="text-xs text-[#57534E] mt-2 leading-relaxed">
                      Up to 18% off curated dining and airtight spice rack bundles.
                    </p>
                  </div>
                  <button
                    type="button"
                    onClick={() => navigateToSection('catalog', 'kitchen-dining')}
                    className="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#0F4C3A] hover:underline self-start cursor-pointer"
                  >
                    Shop Kitchen <ArrowRight className="w-3.5 h-3.5" />
                  </button>
                </div>
                <div className="sm:col-span-5 aspect-[4/3] sm:aspect-auto bg-[#F3F1EC]">
                  <SmartImage
                    src="/src/assets/images/prod_acacia_organizer_1795365629668.jpg"
                    alt="Acacia kitchen organizer"
                    className="w-full h-full object-cover"
                  />
                </div>
              </div>

              <div className="relative rounded-xl overflow-hidden border border-[#E5E2DC] bg-white grid grid-cols-1 sm:grid-cols-12 items-stretch">
                <div className="sm:col-span-7 p-6 flex flex-col justify-between">
                  <div>
                    <p className="text-xs font-semibold text-[#0F4C3A] uppercase tracking-wider">
                      Warm Ambient Lighting
                    </p>
                    <h2 className="font-display text-2xl font-semibold text-[#1C1917] mt-1">
                      Brushed Brass &amp; Teak Accents
                    </h2>
                    <p className="text-xs text-[#57534E] mt-2 leading-relaxed">
                      220V Bangladesh-ready fixtures with energy-saving warm LEDs.
                    </p>
                  </div>
                  <button
                    type="button"
                    onClick={() => navigateToSection('catalog', 'home-furniture')}
                    className="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#0F4C3A] hover:underline self-start cursor-pointer"
                  >
                    Shop Home Decor <ArrowRight className="w-3.5 h-3.5" />
                  </button>
                </div>
                <div className="sm:col-span-5 aspect-[4/3] sm:aspect-auto bg-[#F3F1EC]">
                  <SmartImage
                    src="/src/assets/images/prod_brass_table_lamp_1795365629582.jpg"
                    alt="Brushed brass table lamp"
                    className="w-full h-full object-cover"
                  />
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* 3. Featured Product Categories */}
        <section id="categories" className="py-14 sm:py-16 border-b border-[#E5E2DC]">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
              <div>
                <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                  Browse Departments
                </p>
                <h2 className="font-display text-3xl sm:text-4xl font-semibold text-[#1C1917] mt-1">
                  Featured Categories
                </h2>
              </div>
              <button
                type="button"
                onClick={() => {
                  setActiveCategory('all');
                  setActiveCollection('all');
                  navigateToSection('catalog');
                }}
                className="text-sm font-semibold text-[#0F4C3A] hover:underline inline-flex items-center gap-1.5 self-start sm:self-auto cursor-pointer"
              >
                View All Products <ArrowRight className="w-4 h-4" />
              </button>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              {STORE_CATEGORIES.map((cat) => (
                <button
                  key={cat.slug}
                  type="button"
                  onClick={() => navigateToSection('catalog', cat.slug)}
                  className="group text-left bg-white border border-[#E5E2DC] rounded-xl overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md cursor-pointer"
                >
                  <div className="aspect-[4/3] bg-[#F3F1EC] overflow-hidden">
                    <SmartImage
                      src={cat.image}
                      alt={cat.name}
                      className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]"
                    />
                  </div>
                  <div className="p-4 sm:p-5">
                    <div className="flex items-baseline justify-between gap-2">
                      <h3 className="text-base font-semibold text-[#1C1917] group-hover:text-[#0F4C3A] transition-colors">
                        {cat.name}
                      </h3>
                      <span className="text-xs text-[#78716C] tabular-nums shrink-0">
                        {cat.itemCount} items
                      </span>
                    </div>
                    <p className="text-xs text-[#57534E] mt-1 line-clamp-1">{cat.subtitle}</p>
                  </div>
                </button>
              ))}
            </div>
          </div>
        </section>

        {/* 4. Dynamic Product Showcase (Best Selling / Featured / Latest / Special Discounts) */}
        <section id="catalog" className="py-14 sm:py-16 bg-white border-b border-[#E5E2DC]">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-8">
              <div>
                <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                  Curated Catalog Preview
                </p>
                <h2 className="font-display text-3xl sm:text-4xl font-semibold text-[#1C1917] mt-1">
                  Storefront Product Showcase
                </h2>
              </div>

              {/* Collection Segmented Tabs */}
              <div className="flex flex-wrap items-center gap-1.5 p-1 bg-[#F3F1EC] rounded-xl self-start lg:self-auto">
                {[
                  { id: 'best-selling', label: 'Best Selling' },
                  { id: 'featured', label: 'Featured' },
                  { id: 'latest', label: 'Latest Arrivals' },
                  { id: 'discount', label: 'Special Discounts' },
                  { id: 'all', label: 'All Items' },
                ].map((tab) => (
                  <button
                    key={tab.id}
                    type="button"
                    onClick={() => setActiveCollection(tab.id as CollectionTab)}
                    className={`px-3.5 py-2 text-xs font-semibold rounded-lg whitespace-nowrap shrink-0 transition-colors cursor-pointer ${
                      activeCollection === tab.id
                        ? 'bg-white text-[#1C1917] shadow-sm'
                        : 'text-[#57534E] hover:text-[#1C1917]'
                    }`}
                  >
                    {tab.label}
                  </button>
                ))}
              </div>
            </div>

            {/* Category & Sort Utility Bar */}
            <div className="flex flex-wrap items-center justify-between gap-4 pb-6 mb-8 border-b border-[#F3F1EC]">
              <div className="flex flex-wrap items-center gap-2">
                <button
                  type="button"
                  onClick={() => setActiveCategory('all')}
                  className={`px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors whitespace-nowrap shrink-0 cursor-pointer ${
                    activeCategory === 'all'
                      ? 'bg-[#1C1917] text-white border-[#1C1917]'
                      : 'bg-[#FAF9F6] text-[#44403C] border-[#E5E2DC] hover:bg-[#EFECE5]'
                  }`}
                >
                  All Categories
                </button>
                {STORE_CATEGORIES.map((cat) => (
                  <button
                    key={cat.slug}
                    type="button"
                    onClick={() => setActiveCategory(cat.slug)}
                    className={`px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors whitespace-nowrap shrink-0 cursor-pointer ${
                      activeCategory === cat.slug
                        ? 'bg-[#1C1917] text-white border-[#1C1917]'
                        : 'bg-[#FAF9F6] text-[#44403C] border-[#E5E2DC] hover:bg-[#EFECE5]'
                    }`}
                  >
                    {cat.name}
                  </button>
                ))}
              </div>

              <div className="flex items-center gap-3">
                <span className="text-xs text-[#78716C] tabular-nums">
                  Showing {filteredCatalog.length} products
                </span>
                <select
                  aria-label="Sort products"
                  value={sortBy}
                  onChange={(e) => setSortBy(e.target.value as SortOption)}
                  className="h-9 px-3 text-xs font-medium bg-[#FAF9F6] border border-[#E5E2DC] rounded-lg text-[#1C1917] focus:outline-none focus:border-[#0F4C3A]"
                >
                  <option value="featured">Sort: Featured</option>
                  <option value="price-asc">Price: Low to High</option>
                  <option value="price-desc">Price: High to Low</option>
                  <option value="rating">Customer Rating</option>
                </select>
              </div>
            </div>

            {filteredCatalog.length === 0 ? (
              <div className="py-16 text-center bg-[#FAF9F6] rounded-xl border border-[#E5E2DC]">
                <p className="text-base font-semibold text-[#1C1917]">
                  No products match the current filter.
                </p>
                <button
                  type="button"
                  onClick={() => {
                    setSearchQuery('');
                    setActiveCategory('all');
                    setActiveCollection('all');
                  }}
                  className="mt-4 px-4 py-2 bg-[#0F4C3A] text-white text-xs font-semibold rounded-lg cursor-pointer"
                >
                  Reset Filters
                </button>
              </div>
            ) : (
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {filteredCatalog.map((product) => (
                  <ProductCard
                    key={product.id}
                    product={product}
                    isWishlisted={wishlistIds.includes(product.id)}
                    onToggleWishlist={handleToggleWishlist}
                    onAddToCart={(p) => handleAddToCart(p, 1)}
                    onOpenDetail={(p) => setDetailProduct(p)}
                  />
                ))}
              </div>
            )}
          </div>
        </section>

        {/* 5. Category-Based Curated Collection Spotlight */}
        <section id="curated-collections" className="py-14 sm:py-16 border-b border-[#E5E2DC]">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
              <div>
                <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                  Category-Based Spotlight
                </p>
                <h2 className="font-display text-3xl sm:text-4xl font-semibold text-[#1C1917] mt-1">
                  Shop by Room &amp; Ritual
                </h2>
              </div>
              <div className="flex flex-wrap gap-1.5 p-1 bg-[#EFECE5] rounded-xl self-start">
                {STORE_CATEGORIES.map((cat) => (
                  <button
                    key={cat.slug}
                    type="button"
                    onClick={() => setSpotlightCategory(cat.slug)}
                    className={`px-3.5 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap shrink-0 transition-colors cursor-pointer ${
                      spotlightCategory === cat.slug
                        ? 'bg-white text-[#1C1917] shadow-sm'
                        : 'text-[#57534E] hover:text-[#1C1917]'
                    }`}
                  >
                    {cat.name}
                  </button>
                ))}
              </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {spotlightProducts.map((product) => (
                <ProductCard
                  key={`spot-${product.id}`}
                  product={product}
                  isWishlisted={wishlistIds.includes(product.id)}
                  onToggleWishlist={handleToggleWishlist}
                  onAddToCart={(p) => handleAddToCart(p, 1)}
                  onOpenDetail={(p) => setDetailProduct(p)}
                />
              ))}
            </div>
          </div>
        </section>

        {/* 6. Promotional Offer Section (Clean Editorial Value Band) */}
        <section className="py-14 sm:py-16 bg-[#0F4C3A] text-white">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div className="lg:col-span-7">
              <p className="text-xs font-semibold uppercase tracking-[0.14em] text-white/75">
                Seasonal Privilege · Nationwide Bangladesh
              </p>
              <h2 className="font-display text-3xl sm:text-4xl lg:text-[42px] font-semibold leading-tight mt-2 text-balance">
                Complimentary Dhaka Metro Delivery Above ৳2,500
              </h2>
              <p className="mt-3 text-sm sm:text-base text-white/85 max-w-xl leading-relaxed">
                Pay securely with Cash on Delivery upon parcel inspection, or settle instantly via bKash and Nagad. Same-day dispatch on orders placed before 2:00 PM.
              </p>
            </div>
            <div className="lg:col-span-5 flex flex-wrap lg:justify-end gap-3">
              <button
                type="button"
                onClick={() => {
                  setActiveCollection('discount');
                  setActiveCategory('all');
                  navigateToSection('catalog');
                }}
                className="px-6 py-3 bg-white text-[#0F4C3A] hover:bg-[#FAF9F6] text-sm font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer"
              >
                Explore Discounted Goods
              </button>
              <button
                type="button"
                onClick={() => setCartOpen(true)}
                className="px-6 py-3 border border-white/35 text-white hover:bg-white/10 text-sm font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer"
              >
                View Bag ({totalCartItems})
              </button>
            </div>
          </div>
        </section>

        {/* 7. Why Choose Us / About TamanBD */}
        <section id="why-choose-us" className="py-14 sm:py-16 bg-white border-b border-[#E5E2DC]">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div className="max-w-xl mb-10">
              <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                Service Standards
              </p>
              <h2 className="font-display text-3xl sm:text-4xl font-semibold text-[#1C1917] mt-1">
                Why Shop with TamanBD
              </h2>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
              {[
                {
                  icon: ShieldCheck,
                  title: 'Verified Quality Craft',
                  desc: 'Every teak shelf, ceramic piece, and botanical batch is inspected at our Dhaka hub prior to dispatch.',
                },
                {
                  icon: Wallet,
                  title: 'COD, bKash & Nagad',
                  desc: 'Flexible payment at your doorstep across Bangladesh or instant mobile wallet checkout.',
                },
                {
                  icon: Truck,
                  title: '64-District Delivery',
                  desc: '24-hour courier inside Dhaka Metro and reliable 48–72 hour insured shipping nationwide.',
                },
                {
                  icon: RotateCcw,
                  title: '7-Day Easy Returns',
                  desc: 'Hassle-free replacement or refund if an item arrives damaged or differs from specifications.',
                },
              ].map((pillar) => {
                const Icon = pillar.icon;
                return (
                  <div key={pillar.title} className="pt-4 border-t border-[#E5E2DC]">
                    <Icon className="w-5 h-5 text-[#0F4C3A] mb-3" strokeWidth={1.75} />
                    <h3 className="text-base font-semibold text-[#1C1917]">{pillar.title}</h3>
                    <p className="text-sm text-[#57534E] mt-1.5 leading-relaxed">{pillar.desc}</p>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* 8. Customer Reviews */}
        <section id="reviews" className="py-14 sm:py-16 border-b border-[#E5E2DC]">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
              <div>
                <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                  Verified Buyers
                </p>
                <h2 className="font-display text-3xl sm:text-4xl font-semibold text-[#1C1917] mt-1">
                  Trusted Across Bangladesh
                </h2>
              </div>
              <p className="text-sm text-[#57534E] tabular-nums">
                4.9 / 5.0 average rating across 1,420+ delivered orders
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              {TESTIMONIALS.map((review) => (
                <article
                  key={review.id}
                  className="bg-white border border-[#E5E2DC] rounded-xl p-6 flex flex-col justify-between gap-5"
                >
                  <div>
                    <div className="flex items-center gap-0.5 text-[#B45309] mb-3">
                      {Array.from({ length: review.rating }).map((_, i) => (
                        <Star key={i} className="w-3.5 h-3.5 fill-current" />
                      ))}
                    </div>
                    <p className="text-sm text-[#1C1917] leading-relaxed">
                      &ldquo;{review.quote}&rdquo;
                    </p>
                  </div>
                  <div className="pt-4 border-t border-[#F3F1EC]">
                    <p className="text-sm font-semibold text-[#1C1917]">{review.name}</p>
                    <p className="text-xs text-[#78716C] mt-0.5">
                      {review.location} <span aria-hidden="true">·</span> {review.purchasedItem}
                    </p>
                  </div>
                </article>
              ))}
            </div>
          </div>
        </section>

        {/* 9. Latest Blog Posts / Journal */}
        <section id="journal" className="py-14 sm:py-16 bg-white border-b border-[#E5E2DC]">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div className="mb-10">
              <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                Editorial Journal
              </p>
              <h2 className="font-display text-3xl sm:text-4xl font-semibold text-[#1C1917] mt-1">
                Stories &amp; Buying Guides
              </h2>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
              {JOURNAL_POSTS.map((post) => (
                <article key={post.id} className="group flex flex-col">
                  <div className="aspect-[4/3] rounded-xl overflow-hidden bg-[#F3F1EC] border border-[#E5E2DC] mb-4">
                    <SmartImage
                      src={post.image}
                      alt={post.title}
                      className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]"
                    />
                  </div>
                  <div className="flex items-center gap-2 text-xs text-[#78716C] mb-1.5">
                    <span className="font-medium text-[#0F4C3A]">{post.category}</span>
                    <span aria-hidden="true">·</span>
                    <span>{post.date}</span>
                    <span aria-hidden="true">·</span>
                    <span>{post.readTime}</span>
                  </div>
                  <h3 className="font-display text-xl sm:text-2xl font-semibold text-[#1C1917] leading-snug group-hover:text-[#0F4C3A] transition-colors">
                    {post.title}
                  </h3>
                  <p className="text-sm text-[#57534E] mt-2 leading-relaxed line-clamp-2">
                    {post.excerpt}
                  </p>
                </article>
              ))}
            </div>
          </div>
        </section>

        {/* 10. Newsletter Subscription */}
        <section className="py-14 sm:py-16">
          <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div className="bg-[#F3F1EC] border border-[#E5E2DC] rounded-2xl p-6 sm:p-10 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
              <div className="lg:col-span-6">
                <p className="text-xs font-semibold uppercase tracking-[0.12em] text-[#0F4C3A]">
                  Restock &amp; Dispatch Notes
                </p>
                <h2 className="font-display text-2xl sm:text-3xl font-semibold text-[#1C1917] mt-1">
                  Receive New Artisan Drops &amp; Seasonal Offers
                </h2>
                <p className="text-sm text-[#57534E] mt-1.5">
                  Monthly highlights from Dhaka workshops and 5% off your first online order.
                </p>
              </div>
              <div className="lg:col-span-6">
                {newsletterSubscribed ? (
                  <div className="flex items-center gap-2.5 p-4 bg-white border border-[#0F4C3A]/30 rounded-xl text-sm font-medium text-[#0F4C3A]">
                    <Check className="w-4 h-4 shrink-0" />
                    <span>Subscribed {newsletterEmail}. Welcome to TamanBD.</span>
                  </div>
                ) : (
                  <form
                    onSubmit={(e) => {
                      e.preventDefault();
                      if (newsletterEmail.trim()) {
                        setNewsletterSubscribed(true);
                      }
                    }}
                    className="flex flex-col sm:flex-row gap-3"
                  >
                    <input
                      type="email"
                      required
                      value={newsletterEmail}
                      onChange={(e) => setNewsletterEmail(e.target.value)}
                      placeholder="Enter your email address"
                      aria-label="Email address for newsletter"
                      className="flex-1 h-11 px-4 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
                    />
                    <button
                      type="submit"
                      className="h-11 px-6 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
                    >
                      Subscribe
                    </button>
                  </form>
                )}
              </div>
            </div>
          </div>
        </section>
      </main>

      {/* 11. Redesigned Blue 4-Column Footer */}
      <footer id="footer-contact" className="bg-[#1B4388] text-white pt-14 pb-8">
        <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/20">
            {/* Column 1 – About TamanBD */}
            <div className="space-y-4">
              <a
                href="#top"
                onClick={(e) => {
                  e.preventDefault();
                  window.scrollTo({ top: 0, behavior: 'smooth' });
                }}
                className="font-display text-3xl font-semibold text-white inline-block"
              >
                TamanBD
              </a>
              <p className="text-sm text-[#DBEAFE] leading-relaxed">
                Site tagline / short company introduction editable through WordPress Customizer or Elementor.
              </p>
              <div className="pt-1 flex items-center gap-2.5">
                {[
                  { label: 'Facebook', icon: Facebook },
                  { label: 'Instagram', icon: Instagram },
                  { label: 'YouTube', icon: Youtube },
                  { label: 'WhatsApp', icon: MessageCircle },
                ].map((social) => {
                  const Icon = social.icon;
                  return (
                    <a
                      key={social.label}
                      href="#footer-contact"
                      onClick={(e) => {
                        e.preventDefault();
                        showToast(`${social.label} URL configurable in WordPress Customizer / Elementor`);
                      }}
                      aria-label={social.label}
                      className="w-9 h-9 rounded-lg border border-white/20 bg-white/10 hover:border-[#F4A62A] hover:text-[#F4A62A] text-[#DBEAFE] flex items-center justify-center transition-colors"
                    >
                      <Icon className="w-4 h-4" strokeWidth={1.75} />
                    </a>
                  );
                })}
              </div>
            </div>

            {/* Column 2 – Quick Links */}
            <div className="space-y-4">
              <h3 className="text-xs font-semibold uppercase tracking-[0.1em] text-white pb-2 border-b border-white/15">
                Quick Links
              </h3>
              <ul className="space-y-2.5 text-sm text-[#DBEAFE]">
                <li>
                  <button
                    type="button"
                    onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Home
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => navigateToSection('why-choose-us')}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    About Us
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => navigateToSection('footer-contact')}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Contact Us
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => navigateToSection('catalog')}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Shop
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => navigateToSection('journal')}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Blog
                  </button>
                </li>
              </ul>
            </div>

            {/* Column 3 – Customer Service */}
            <div className="space-y-4">
              <h3 className="text-xs font-semibold uppercase tracking-[0.1em] text-white pb-2 border-b border-white/15">
                Customer Service
              </h3>
              <ul className="space-y-2.5 text-sm text-[#DBEAFE]">
                <li>
                  <button
                    type="button"
                    onClick={() => setAccountOpen(true)}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    My Account
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => setAccountOpen(true)}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Track Order
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => showToast('Links to WordPress Privacy Policy page')}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Privacy Policy
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => showToast('Links to Return & Refund Policy page')}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Return &amp; Refund Policy
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    onClick={() => showToast('Links to Terms & Conditions page')}
                    className="hover:text-[#F4A62A] transition-colors cursor-pointer"
                  >
                    Terms &amp; Conditions
                  </button>
                </li>
              </ul>
            </div>

            {/* Column 4 – Contact Information (Editable Placeholders / WP Settings) */}
            <div className="space-y-4">
              <h3 className="text-xs font-semibold uppercase tracking-[0.1em] text-white pb-2 border-b border-white/15">
                Contact Information
              </h3>
              <ul className="space-y-3 text-sm text-[#DBEAFE]">
                <li className="flex items-start gap-2.5">
                  <MapPin className="w-4 h-4 text-[#F4A62A] shrink-0 mt-1" />
                  <span>Business Address (Editable in WordPress / Elementor)</span>
                </li>
                <li className="flex items-center gap-2.5">
                  <Phone className="w-4 h-4 text-[#F4A62A] shrink-0" />
                  <span className="tabular-nums">+880 1XXX-XXXXXX</span>
                </li>
                <li className="flex items-center gap-2.5">
                  <Mail className="w-4 h-4 text-[#F4A62A] shrink-0" />
                  <span>admin@yourdomain.com</span>
                </li>
                <li className="flex items-center gap-2.5">
                  <MessageCircle className="w-4 h-4 text-[#F4A62A] shrink-0" />
                  <span className="tabular-nums">WhatsApp: +880 1XXX-XXXXXX</span>
                </li>
              </ul>
            </div>
          </div>

          <div className="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#DBEAFE]">
            <p>© {new Date().getFullYear()} TamanBD. All rights reserved.</p>
            <p className="text-[#DBEAFE]">
              Cash on Delivery <span className="text-[#F4A62A]" aria-hidden="true">·</span> bKash <span className="text-[#F4A62A]" aria-hidden="true">·</span> Nagad <span className="text-[#F4A62A]" aria-hidden="true">·</span> Visa / Mastercard
            </p>
          </div>
        </div>
      </footer>

      {/* Toast Feedback */}
      {toastMessage && (
        <div
          role="status"
          className="fixed bottom-16 right-5 z-50 bg-[#172337] text-white text-xs font-medium px-4 py-3 rounded-lg shadow-lg border border-[#F4A62A]/40"
        >
          {toastMessage}
        </div>
      )}

      {/* Desktop / Mobile Viewport Preview Switcher (Hidden inside mobile iframe) */}
      {!isEmbeddedMobileFrame && (
        <div className="fixed bottom-4 left-4 z-40 hidden lg:flex items-center gap-1 p-1 bg-[#172337] text-white rounded-xl shadow-lg border border-white/15">
          <button
            type="button"
            onClick={() => setViewportMode('desktop')}
            className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer ${
              viewportMode === 'desktop' ? 'bg-[#F4A62A] text-[#172337]' : 'text-[#C5CEDA] hover:text-white'
            }`}
          >
            <Monitor className="w-3.5 h-3.5" />
            Desktop Preview
          </button>
          <button
            type="button"
            onClick={() => setViewportMode('mobile')}
            className={`inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors cursor-pointer ${
              viewportMode === 'mobile' ? 'bg-[#F4A62A] text-[#172337]' : 'text-[#C5CEDA] hover:text-white'
            }`}
          >
            <Smartphone className="w-3.5 h-3.5" />
            Mobile Preview (390px)
          </button>
        </div>
      )}

      {/* Interactive Mobile Viewport Modal Frame */}
      {!isEmbeddedMobileFrame && viewportMode === 'mobile' && (
        <div className="fixed inset-0 z-50 bg-[#0F172A]/85 backdrop-blur-sm flex flex-col items-center justify-center p-4">
          <div className="mb-3 flex items-center justify-between w-[390px] max-w-full text-white">
            <span className="text-xs font-semibold tracking-wide text-[#C5CEDA]">
              Mobile Responsive Preview (390 × 760)
            </span>
            <button
              type="button"
              onClick={() => setViewportMode('desktop')}
              className="px-3 py-1.5 bg-[#F4A62A] text-[#172337] text-xs font-semibold rounded-lg cursor-pointer"
            >
              Back to Desktop
            </button>
          </div>
          <div className="w-[390px] h-[760px] max-h-[82vh] bg-white rounded-2xl overflow-hidden shadow-2xl border-4 border-[#1E293B]">
            <iframe
              src="/?preview=mobile"
              title="TamanBD Mobile Responsive Preview"
              className="w-full h-full border-0"
            />
          </div>
        </div>
      )}

      {/* Drawers & Modals */}
      <CartDrawer
        open={cartOpen}
        onClose={() => setCartOpen(false)}
        items={cart}
        onUpdateQty={handleUpdateQty}
        onRemoveItem={handleRemoveCartItem}
        onClearCart={() => setCart([])}
      />

      <WishlistDrawer
        open={wishlistOpen}
        onClose={() => setWishlistOpen(false)}
        products={wishlistedProducts}
        onRemove={handleToggleWishlist}
        onMoveToCart={(product) => {
          handleAddToCart(product, 1);
          handleToggleWishlist(product.id);
        }}
      />

      <ProductDetailModal
        product={detailProduct}
        onClose={() => setDetailProduct(null)}
        onAddToCart={handleAddToCart}
        onBuyNow={handleBuyNow}
      />

      <AccountModal open={accountOpen} onClose={() => setAccountOpen(false)} />
    </div>
  );
}
