import { useEffect, useRef, useState } from 'react';
import {
  ChevronDown,
  ChevronRight,
  Heart,
  LayoutGrid,
  Menu,
  Phone,
  Search,
  ShoppingBag,
  User,
  X,
} from 'lucide-react';
import { STORE_CATEGORIES } from '../data/previewCatalog';
import { StoreProduct } from '../types/store';

interface HeaderProps {
  searchQuery: string;
  onSearchChange: (q: string) => void;
  cartCount: number;
  cartSubtotal: number;
  wishlistCount: number;
  onOpenCart: () => void;
  onOpenWishlist: () => void;
  onOpenAccount: () => void;
  onNavigateSection: (sectionId: string, categorySlug?: string) => void;
  quickSearchResults: StoreProduct[];
  onSelectProduct: (product: StoreProduct) => void;
}

export function Header({
  searchQuery,
  onSearchChange,
  cartCount,
  cartSubtotal,
  wishlistCount,
  onOpenCart,
  onOpenWishlist,
  onOpenAccount,
  onNavigateSection,
  quickSearchResults,
  onSelectProduct,
}: HeaderProps) {
  const [showAnnouncement, setShowAnnouncement] = useState(true);
  const [allCatsOpen, setAllCatsOpen] = useState(false);
  const [expandedCatSlug, setExpandedCatSlug] = useState<string | null>(STORE_CATEGORIES[0]?.slug ?? null);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [mobileCatsDrawerOpen, setMobileCatsDrawerOpen] = useState(false);
  const [mobileSearchOpen, setMobileSearchOpen] = useState(false);
  const [searchFocused, setSearchFocused] = useState(false);

  const catDropdownRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (catDropdownRef.current && !catDropdownRef.current.contains(event.target as Node)) {
        setAllCatsOpen(false);
      }
    };
    const handleKeyDown = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        setAllCatsOpen(false);
        setMobileMenuOpen(false);
        setMobileCatsDrawerOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    document.addEventListener('keydown', handleKeyDown);
    return () => {
      document.removeEventListener('mousedown', handleClickOutside);
      document.removeEventListener('keydown', handleKeyDown);
    };
  }, []);

  const handleNavAction = (target: string, categorySlug?: string) => {
    setAllCatsOpen(false);
    setMobileMenuOpen(false);
    setMobileCatsDrawerOpen(false);
    if (target === 'top') {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }
    if (target === 'account') {
      onOpenAccount();
      return;
    }
    onNavigateSection(target, categorySlug);
  };

  const toggleSubcategory = (slug: string) => {
    setExpandedCatSlug((prev) => (prev === slug ? null : slug));
  };

  const mainNavItems = [
    { label: 'Home', target: 'top' },
    { label: 'About', target: 'why-choose-us' },
    { label: 'Shop', target: 'catalog' },
    { label: 'Contact', target: 'footer-contact' },
  ];

  return (
    <>
      {/* 1. Top Announcement Bar (#172337 Dark Navy) */}
      {showAnnouncement && (
        <div className="bg-[#172337] text-white text-xs py-2 px-4 relative z-40">
          <div className="max-w-[1360px] mx-auto flex items-center justify-between gap-4">
            <div className="flex flex-wrap items-center justify-between gap-x-6 gap-y-1 flex-1 min-w-0">
              <p className="font-medium tracking-wide truncate">
                <span className="text-[#F4A62A] font-semibold">Promo:</span> Free Dhaka delivery on orders above ৳2,500 · Cash on Delivery nationwide
              </p>
              <p className="hidden sm:flex items-center gap-1.5 text-[#C5CEDA] shrink-0">
                <Phone className="w-3.5 h-3.5 text-[#F4A62A]" />
                <span>Support: +880 1XXX-XXXXXX</span>
              </p>
            </div>
            <button
              type="button"
              onClick={() => setShowAnnouncement(false)}
              aria-label="Dismiss announcement"
              className="shrink-0 p-1 rounded hover:bg-white/10 text-[#C5CEDA] hover:text-white transition-colors cursor-pointer"
            >
              <X className="w-3.5 h-3.5" />
            </button>
          </div>
        </div>
      )}

      {/* 2. Main Header (White Background) */}
      <header className="sticky top-0 z-30 bg-white border-b border-[#E5E2DC] shadow-[0_1px_2px_rgba(23,35,55,0.04)]">
        <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 h-16 lg:h-[72px] flex items-center justify-between gap-4 lg:gap-8">
          {/* Left: Mobile Menu + TamanBD Logo */}
          <div className="flex items-center gap-2.5 shrink-0">
            <button
              type="button"
              onClick={() => setMobileMenuOpen(true)}
              aria-label="Open menu"
              className="lg:hidden p-2 -ml-2 text-[#172337] hover:bg-[#F3F1EC] rounded-lg transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer"
            >
              <Menu className="w-5 h-5" />
            </button>
            <a
              href="#top"
              onClick={(e) => {
                e.preventDefault();
                handleNavAction('top');
              }}
              className="font-display text-2xl sm:text-[30px] font-semibold tracking-tight text-[#172337] whitespace-nowrap shrink-0"
            >
              TamanBD
            </a>
          </div>

          {/* Center: Wide WooCommerce Product Search Bar */}
          <div className="relative hidden md:block flex-1 max-w-2xl">
            <div className="relative flex items-center">
              <Search className="w-4 h-4 text-[#78716C] absolute left-3.5 pointer-events-none" />
              <input
                type="search"
                value={searchQuery}
                onChange={(e) => onSearchChange(e.target.value)}
                onFocus={() => setSearchFocused(true)}
                onBlur={() => setTimeout(() => setSearchFocused(false), 160)}
                placeholder="Search WooCommerce products, categories, or SKUs..."
                aria-label="Search products"
                className="w-full h-11 pl-10 pr-24 text-sm bg-[#FAF9F6] border border-[#E5E2DC] rounded-lg text-[#172337] placeholder:text-[#78716C] focus:outline-none focus:bg-white focus:border-[#172337] transition-colors"
              />
              <button
                type="button"
                onClick={() => handleNavAction('catalog')}
                className="absolute right-1.5 h-8 px-3.5 bg-[#172337] hover:bg-[#23344F] text-white text-xs font-semibold rounded-md transition-colors cursor-pointer"
              >
                Search
              </button>
            </div>

            {searchFocused && searchQuery.trim().length > 0 && (
              <div className="absolute left-0 right-0 top-12 bg-white border border-[#E5E2DC] rounded-xl shadow-xl overflow-hidden z-50">
                {quickSearchResults.length === 0 ? (
                  <div className="px-4 py-3 text-xs text-[#57534E]">
                    No matching products found for &ldquo;{searchQuery}&rdquo;.
                  </div>
                ) : (
                  <ul className="divide-y divide-[#F3F1EC] max-h-72 overflow-y-auto">
                    {quickSearchResults.slice(0, 5).map((product) => (
                      <li key={product.id}>
                        <button
                          type="button"
                          onMouseDown={() => {
                            onSelectProduct(product);
                            setSearchFocused(false);
                          }}
                          className="w-full px-4 py-2.5 flex items-center justify-between gap-3 text-left hover:bg-[#FAF9F6] transition-colors"
                        >
                          <div className="min-w-0">
                            <p className="text-xs font-semibold text-[#172337] truncate">
                              {product.name}
                            </p>
                            <p className="text-[11px] text-[#78716C] truncate">
                              {product.category}
                            </p>
                          </div>
                          <span className="text-xs font-semibold text-[#172337] tabular-nums shrink-0">
                            ৳{(product.salePrice ?? product.regularPrice).toLocaleString()}
                          </span>
                        </button>
                      </li>
                    ))}
                  </ul>
                )}
              </div>
            )}
          </div>

          {/* Right: My Account, Wishlist & Shopping Cart (with Count + Subtotal) */}
          <div className="flex items-center gap-1.5 sm:gap-3 shrink-0">
            <button
              type="button"
              onClick={() => setMobileSearchOpen((prev) => !prev)}
              aria-label="Toggle search"
              className="md:hidden p-2 text-[#172337] hover:bg-[#F3F1EC] rounded-lg transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer"
            >
              <Search className="w-5 h-5" />
            </button>

            <button
              type="button"
              onClick={onOpenAccount}
              aria-label="My Account"
              className="hidden sm:inline-flex items-center gap-2 px-3 h-10 text-xs font-semibold text-[#172337] hover:bg-[#F3F1EC] rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
            >
              <User className="w-4 h-4 text-[#172337]" strokeWidth={1.8} />
              <span>My Account</span>
            </button>

            <button
              type="button"
              onClick={onOpenWishlist}
              aria-label={`Wishlist (${wishlistCount} items)`}
              className="relative flex items-center justify-center w-10 h-10 text-[#172337] hover:bg-[#F3F1EC] rounded-lg transition-colors shrink-0 cursor-pointer"
            >
              <Heart className="w-[18px] h-[18px]" strokeWidth={1.8} />
              {wishlistCount > 0 && (
                <span className="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-[#F4A62A] text-[#172337] text-[10px] font-bold tabular-nums flex items-center justify-center">
                  {wishlistCount}
                </span>
              )}
            </button>

            <button
              type="button"
              onClick={onOpenCart}
              aria-label={`Shopping cart (${cartCount} items, subtotal ৳${cartSubtotal.toLocaleString()})`}
              className="flex items-center gap-2.5 px-3 sm:px-3.5 h-10 bg-[#172337] hover:bg-[#23344F] text-white text-xs font-semibold rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
            >
              <div className="relative flex items-center">
                <ShoppingBag className="w-4 h-4 text-[#F4A62A]" strokeWidth={1.9} />
              </div>
              <span className="tabular-nums bg-[#F4A62A] text-[#172337] px-1.5 py-0.5 rounded text-[11px] font-bold">
                {cartCount}
              </span>
              <span className="hidden sm:inline tabular-nums">
                ৳{cartSubtotal.toLocaleString()}
              </span>
            </button>
          </div>
        </div>

        {/* Mobile Expandable Search */}
        {mobileSearchOpen && (
          <div className="md:hidden px-4 pb-3 pt-1 border-t border-[#E5E2DC] bg-white">
            <div className="relative">
              <Search className="w-4 h-4 text-[#78716C] absolute left-3 top-1/2 -translate-y-1/2" />
              <input
                type="search"
                value={searchQuery}
                onChange={(e) => onSearchChange(e.target.value)}
                placeholder="Search TamanBD products..."
                className="w-full h-10 pl-9 pr-3 text-sm bg-[#FAF9F6] border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#172337]"
              />
            </div>
          </div>
        )}
      </header>

      {/* 3. Second Navigation Row (Desktop: Left ALL CATEGORIES + Right 4 Main Nav Links) */}
      <div className="bg-white border-b border-[#E5E2DC] relative z-20">
        <div className="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8 h-12 flex items-center justify-between gap-4">
          {/* LEFT SIDE: Prominent ALL CATEGORIES Dropdown Button */}
          <div ref={catDropdownRef} className="relative">
            <button
              type="button"
              onClick={() => {
                if (window.innerWidth < 1024) {
                  setMobileCatsDrawerOpen(true);
                } else {
                  setAllCatsOpen((prev) => !prev);
                }
              }}
              aria-expanded={allCatsOpen}
              aria-haspopup="true"
              className="inline-flex items-center gap-2.5 h-9 px-4 bg-[#172337] hover:bg-[#23344F] text-white text-xs font-semibold tracking-wider uppercase rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
            >
              <LayoutGrid className="w-4 h-4 text-[#F4A62A]" strokeWidth={2} />
              <span>ALL CATEGORIES</span>
              <ChevronDown
                className={`w-3.5 h-3.5 text-[#C5CEDA] transition-transform duration-200 ${
                  allCatsOpen ? 'rotate-180' : ''
                }`}
              />
            </button>

            {/* Desktop Vertical Category Dropdown Panel (Parent + Expandable Subcategories) */}
            {allCatsOpen && (
              <div
                role="menu"
                aria-label="Product Categories"
                className="hidden lg:block absolute left-0 top-full mt-2 w-80 bg-white border border-[#E5E2DC] rounded-xl shadow-xl overflow-hidden z-50 transition-all duration-150"
              >
                <div className="px-4 py-2.5 bg-[#FAF9F6] border-b border-[#E5E2DC] flex items-center justify-between">
                  <span className="text-[11px] font-semibold uppercase tracking-wider text-[#57534E]">
                    WooCommerce Categories
                  </span>
                  <button
                    type="button"
                    onClick={() => {
                      setAllCatsOpen(false);
                      handleNavAction('catalog');
                    }}
                    className="text-[11px] font-semibold text-[#172337] hover:text-[#F4A62A] cursor-pointer"
                  >
                    View All
                  </button>
                </div>

                <ul className="divide-y divide-[#F3F1EC] max-h-[420px] overflow-y-auto">
                  {STORE_CATEGORIES.map((cat) => {
                    const isExpanded = expandedCatSlug === cat.slug;
                    const hasChildren = Boolean(cat.subcategories && cat.subcategories.length > 0);
                    return (
                      <li key={cat.slug}>
                        <div className="flex items-center justify-between hover:bg-[#FAF9F6] transition-colors">
                          <button
                            type="button"
                            onClick={() => handleNavAction('catalog', cat.slug)}
                            className="flex-1 px-4 py-3 text-left text-sm font-medium text-[#172337] hover:text-[#0F4C3A] flex items-center justify-between gap-2 cursor-pointer"
                          >
                            <span className="truncate">{cat.name}</span>
                            <span className="text-[11px] text-[#78716C] tabular-nums">
                              {cat.itemCount}
                            </span>
                          </button>

                          {hasChildren && (
                            <button
                              type="button"
                              onClick={() => toggleSubcategory(cat.slug)}
                              aria-label={`Expand ${cat.name} subcategories`}
                              aria-expanded={isExpanded}
                              className="px-3 py-3 text-[#57534E] hover:text-[#172337] border-l border-[#F3F1EC] cursor-pointer"
                            >
                              <ChevronDown
                                className={`w-4 h-4 transition-transform duration-150 ${
                                  isExpanded ? 'rotate-180 text-[#F4A62A]' : ''
                                }`}
                              />
                            </button>
                          )}
                        </div>

                        {hasChildren && isExpanded && (
                          <ul className="bg-[#FAF9F6] border-t border-[#F3F1EC] py-1.5">
                            {cat.subcategories!.map((sub) => (
                              <li key={sub.name}>
                                <button
                                  type="button"
                                  onClick={() => handleNavAction('catalog', sub.slug)}
                                  className="w-full pl-7 pr-4 py-2 text-left text-xs text-[#44403C] hover:text-[#172337] hover:bg-[#F3F1EC] flex items-center justify-between transition-colors cursor-pointer"
                                >
                                  <span className="flex items-center gap-1.5">
                                    <ChevronRight className="w-3 h-3 text-[#F4A62A]" />
                                    {sub.name}
                                  </span>
                                  <span className="text-[11px] text-[#78716C] tabular-nums">
                                    {sub.itemCount}
                                  </span>
                                </button>
                              </li>
                            ))}
                          </ul>
                        )}
                      </li>
                    );
                  })}
                </ul>
              </div>
            )}
          </div>

          {/* RIGHT SIDE: ONLY 4 Main Navigation Items (Home, About, Shop, Contact) */}
          <nav aria-label="Main navigation" className="hidden lg:flex items-center gap-7">
            {mainNavItems.map((item) => (
              <button
                key={item.label}
                type="button"
                onClick={() => handleNavAction(item.target)}
                className="text-sm font-semibold text-[#172337] hover:text-[#F4A62A] transition-colors whitespace-nowrap shrink-0 py-1 cursor-pointer"
              >
                {item.label}
              </button>
            ))}
          </nav>

          {/* Mobile quick link to Shop */}
          <button
            type="button"
            onClick={() => handleNavAction('catalog')}
            className="lg:hidden text-xs font-semibold text-[#172337] hover:text-[#F4A62A] whitespace-nowrap cursor-pointer"
          >
            Shop All →
          </button>
        </div>
      </div>

      {/* Mobile Hamburger Menu Drawer (4 Main Nav Items + Account) */}
      {mobileMenuOpen && (
        <div className="fixed inset-0 z-50 lg:hidden flex">
          <div className="fixed inset-0 bg-black/45" onClick={() => setMobileMenuOpen(false)} />
          <div className="relative w-72 max-w-[85vw] bg-white h-full shadow-2xl flex flex-col justify-between p-6 z-10 overflow-y-auto">
            <div>
              <div className="flex items-center justify-between pb-4 border-b border-[#E5E2DC]">
                <span className="font-display text-2xl font-semibold text-[#172337]">
                  TamanBD
                </span>
                <button
                  type="button"
                  onClick={() => setMobileMenuOpen(false)}
                  aria-label="Close navigation menu"
                  className="p-2 -mr-2 text-[#172337] rounded-lg hover:bg-[#F3F1EC] cursor-pointer"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>
              <nav aria-label="Mobile main navigation" className="mt-5 flex flex-col gap-1">
                {mainNavItems.map((item) => (
                  <button
                    key={item.label}
                    type="button"
                    onClick={() => handleNavAction(item.target)}
                    className="text-left px-3 py-3 text-sm font-semibold text-[#172337] hover:bg-[#FAF9F6] rounded-lg cursor-pointer"
                  >
                    {item.label}
                  </button>
                ))}
              </nav>
              <div className="mt-4 pt-4 border-t border-[#E5E2DC]">
                <button
                  type="button"
                  onClick={() => {
                    setMobileMenuOpen(false);
                    setMobileCatsDrawerOpen(true);
                  }}
                  className="w-full px-3 py-3 bg-[#172337] text-white text-xs font-semibold uppercase tracking-wider rounded-lg flex items-center justify-between cursor-pointer"
                >
                  <span className="flex items-center gap-2">
                    <LayoutGrid className="w-4 h-4 text-[#F4A62A]" />
                    Browse All Categories
                  </span>
                  <ChevronRight className="w-4 h-4" />
                </button>
              </div>
            </div>

            <div className="pt-5 border-t border-[#E5E2DC]">
              <button
                type="button"
                onClick={() => handleNavAction('account')}
                className="w-full flex items-center gap-2.5 px-3 py-2.5 text-sm font-semibold text-[#172337] bg-[#FAF9F6] border border-[#E5E2DC] rounded-lg cursor-pointer"
              >
                <User className="w-4 h-4" />
                My Account
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Mobile Slide-Out Category Drawer (Expandable Parent & Child Categories) */}
      {mobileCatsDrawerOpen && (
        <div className="fixed inset-0 z-50 lg:hidden flex">
          <div
            className="fixed inset-0 bg-black/45"
            onClick={() => setMobileCatsDrawerOpen(false)}
          />
          <aside
            aria-label="Mobile Product Categories"
            className="relative w-80 max-w-[88vw] bg-white h-full shadow-2xl flex flex-col z-10 overflow-y-auto"
          >
            <div className="px-5 py-4 bg-[#172337] text-white flex items-center justify-between">
              <div className="flex items-center gap-2">
                <LayoutGrid className="w-4 h-4 text-[#F4A62A]" />
                <span className="text-xs font-semibold uppercase tracking-wider">
                  All Categories
                </span>
              </div>
              <button
                type="button"
                onClick={() => setMobileCatsDrawerOpen(false)}
                aria-label="Close categories drawer"
                className="p-1.5 rounded-lg hover:bg-white/10 text-white cursor-pointer"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <ul className="divide-y divide-[#F3F1EC] flex-1">
              {STORE_CATEGORIES.map((cat) => {
                const isExpanded = expandedCatSlug === cat.slug;
                const hasChildren = Boolean(cat.subcategories && cat.subcategories.length > 0);
                return (
                  <li key={cat.slug}>
                    <div className="flex items-center justify-between">
                      <button
                        type="button"
                        onClick={() => handleNavAction('catalog', cat.slug)}
                        className="flex-1 px-5 py-3.5 text-left text-sm font-semibold text-[#172337] flex items-center justify-between cursor-pointer"
                      >
                        <span>{cat.name}</span>
                        <span className="text-xs text-[#78716C] tabular-nums">{cat.itemCount}</span>
                      </button>
                      {hasChildren && (
                        <button
                          type="button"
                          onClick={() => toggleSubcategory(cat.slug)}
                          aria-label={`Toggle ${cat.name} subcategories`}
                          aria-expanded={isExpanded}
                          className="px-4 py-3.5 text-[#172337] border-l border-[#F3F1EC] cursor-pointer"
                        >
                          <ChevronDown
                            className={`w-4 h-4 transition-transform ${
                              isExpanded ? 'rotate-180 text-[#F4A62A]' : ''
                            }`}
                          />
                        </button>
                      )}
                    </div>
                    {hasChildren && isExpanded && (
                      <ul className="bg-[#FAF9F6] border-t border-[#F3F1EC] py-1">
                        {cat.subcategories!.map((sub) => (
                          <li key={sub.name}>
                            <button
                              type="button"
                              onClick={() => handleNavAction('catalog', sub.slug)}
                              className="w-full pl-8 pr-5 py-2.5 text-left text-xs font-medium text-[#44403C] flex items-center justify-between cursor-pointer"
                            >
                              <span className="flex items-center gap-1.5">
                                <ChevronRight className="w-3.5 h-3.5 text-[#F4A62A]" />
                                {sub.name}
                              </span>
                              <span className="text-[11px] text-[#78716C] tabular-nums">
                                {sub.itemCount}
                              </span>
                            </button>
                          </li>
                        ))}
                      </ul>
                    )}
                  </li>
                );
              })}
            </ul>
          </aside>
        </div>
      )}
    </>
  );
}
