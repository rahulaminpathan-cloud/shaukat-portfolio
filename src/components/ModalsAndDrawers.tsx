import { useState, useEffect } from 'react';
import {
  CheckCircle2,
  Heart,
  Minus,
  Plus,
  ShoppingBag,
  Star,
  Trash2,
  X,
} from 'lucide-react';
import { CartItem, StoreProduct } from '../types/store';
import { SmartImage } from './SmartImage';

/* -------------------------------------------------------------------------- */
/* 1. Cart Drawer + COD / Mobile Payment Checkout Preview                     */
/* -------------------------------------------------------------------------- */
interface CartDrawerProps {
  open: boolean;
  onClose: () => void;
  items: CartItem[];
  onUpdateQty: (productId: string, variant: string | undefined, delta: number) => void;
  onRemoveItem: (productId: string, variant: string | undefined) => void;
  onClearCart: () => void;
}

export function CartDrawer({
  open,
  onClose,
  items,
  onUpdateQty,
  onRemoveItem,
  onClearCart,
}: CartDrawerProps) {
  const [step, setStep] = useState<'cart' | 'checkout' | 'confirmed'>('cart');
  const [paymentMethod, setPaymentMethod] = useState<'cod' | 'bkash' | 'nagad'>('cod');
  const [customerName, setCustomerName] = useState('');
  const [phone, setPhone] = useState('');
  const [address, setAddress] = useState('');
  const [district, setDistrict] = useState('Dhaka');
  const [orderNumber, setOrderNumber] = useState('');

  useEffect(() => {
    if (!open && step === 'confirmed') {
      setStep('cart');
    }
  }, [open, step]);

  if (!open) return null;

  const subtotal = items.reduce(
    (sum, item) => sum + (item.product.salePrice ?? item.product.regularPrice) * item.quantity,
    0
  );
  const freeShippingThreshold = 2500;
  const remainingForFree = Math.max(0, freeShippingThreshold - subtotal);
  const deliveryFee = subtotal === 0 ? 0 : remainingForFree === 0 && district === 'Dhaka' ? 0 : district === 'Dhaka' ? 80 : 130;
  const total = subtotal + deliveryFee;

  const handlePlaceOrder = (e: React.FormEvent) => {
    e.preventDefault();
    const randomOrder = `TMB-${Math.floor(1040 + Math.random() * 8900)}`;
    setOrderNumber(randomOrder);
    onClearCart();
    setStep('confirmed');
  };

  return (
    <div className="fixed inset-0 z-50 flex justify-end">
      <div className="fixed inset-0 bg-black/45" onClick={onClose} />
      <aside
        aria-label="Shopping cart"
        className="relative w-full max-w-md bg-[#FAF9F6] h-full shadow-2xl flex flex-col z-10"
      >
        <div className="px-6 py-4 border-b border-[#E5E2DC] bg-white flex items-center justify-between">
          <div>
            <h2 className="font-display text-2xl font-semibold text-[#1C1917]">
              {step === 'cart' ? 'Your Shopping Bag' : step === 'checkout' ? 'Checkout Details' : 'Order Confirmed'}
            </h2>
            <p className="text-xs text-[#78716C]">
              {step === 'cart'
                ? `${items.reduce((s, i) => s + i.quantity, 0)} items · WooCommerce Cart UI Preview`
                : step === 'checkout'
                  ? 'Cash on Delivery & Local Payment Flow'
                  : 'Order summary preview'}
            </p>
          </div>
          <button
            type="button"
            onClick={onClose}
            aria-label="Close cart"
            className="p-2 text-[#1C1917] hover:bg-[#EFECE5] rounded-lg transition-colors cursor-pointer"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {step === 'cart' && (
          <>
            {items.length > 0 && (
              <div className="px-6 py-3 bg-[#F3F1EC] border-b border-[#E5E2DC]">
                <p className="text-xs text-[#44403C] mb-1.5">
                  {remainingForFree === 0
                    ? 'You unlocked free standard delivery inside Dhaka.'
                    : `Add ৳${remainingForFree.toLocaleString()} more for free Dhaka delivery.`}
                </p>
                <div className="h-1.5 w-full bg-[#E5E2DC] rounded-full overflow-hidden">
                  <div
                    className="h-full bg-[#0F4C3A] transition-all duration-300"
                    style={{ width: `${Math.min(100, (subtotal / freeShippingThreshold) * 100)}%` }}
                  />
                </div>
              </div>
            )}

            <div className="flex-1 overflow-y-auto px-6 py-5 space-y-4">
              {items.length === 0 ? (
                <div className="h-full flex flex-col items-center justify-center text-center py-12">
                  <ShoppingBag className="w-10 h-10 text-[#A8A29E] mb-3" strokeWidth={1.5} />
                  <p className="text-base font-semibold text-[#1C1917]">Your shopping bag is empty</p>
                  <p className="text-sm text-[#78716C] mt-1 max-w-xs">
                    Browse our curated Bangladeshi home, kitchen, and botanical goods to add items.
                  </p>
                </div>
              ) : (
                items.map((item) => {
                  const price = item.product.salePrice ?? item.product.regularPrice;
                  return (
                    <div
                      key={`${item.product.id}-${item.selectedVariant ?? 'default'}`}
                      className="flex gap-4 bg-white p-3.5 rounded-xl border border-[#E5E2DC]"
                    >
                      <div className="w-20 h-20 rounded-lg overflow-hidden bg-[#F3F1EC] shrink-0">
                        <SmartImage
                          src={item.product.image}
                          alt={item.product.name}
                          className="w-full h-full object-cover"
                        />
                      </div>
                      <div className="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                          <div className="flex items-start justify-between gap-2">
                            <h3 className="text-sm font-semibold text-[#1C1917] line-clamp-1">
                              {item.product.name}
                            </h3>
                            <button
                              type="button"
                              onClick={() => onRemoveItem(item.product.id, item.selectedVariant)}
                              aria-label={`Remove ${item.product.name}`}
                              className="text-[#78716C] hover:text-red-700 p-0.5 cursor-pointer"
                            >
                              <Trash2 className="w-4 h-4" />
                            </button>
                          </div>
                          {item.selectedVariant && (
                            <p className="text-xs text-[#78716C] mt-0.5">{item.selectedVariant}</p>
                          )}
                        </div>
                        <div className="flex items-center justify-between mt-3">
                          <div className="inline-flex items-center border border-[#E5E2DC] rounded-lg bg-[#FAF9F6]">
                            <button
                              type="button"
                              onClick={() => onUpdateQty(item.product.id, item.selectedVariant, -1)}
                              aria-label="Decrease quantity"
                              className="w-7 h-7 flex items-center justify-center text-[#1C1917] hover:bg-[#EFECE5] cursor-pointer"
                            >
                              <Minus className="w-3 h-3" />
                            </button>
                            <span className="w-7 text-center text-xs font-semibold tabular-nums">
                              {item.quantity}
                            </span>
                            <button
                              type="button"
                              onClick={() => onUpdateQty(item.product.id, item.selectedVariant, 1)}
                              aria-label="Increase quantity"
                              className="w-7 h-7 flex items-center justify-center text-[#1C1917] hover:bg-[#EFECE5] cursor-pointer"
                            >
                              <Plus className="w-3 h-3" />
                            </button>
                          </div>
                          <span className="text-sm font-semibold text-[#1C1917] tabular-nums">
                            ৳{(price * item.quantity).toLocaleString()}
                          </span>
                        </div>
                      </div>
                    </div>
                  );
                })
              )}
            </div>

            {items.length > 0 && (
              <div className="p-6 bg-white border-t border-[#E5E2DC] space-y-4">
                <div className="space-y-1.5 text-sm">
                  <div className="flex justify-between text-[#57534E]">
                    <span>Subtotal</span>
                    <span className="tabular-nums font-medium text-[#1C1917]">
                      ৳{subtotal.toLocaleString()}
                    </span>
                  </div>
                  <div className="flex justify-between text-[#57534E]">
                    <span>Estimated Delivery ({district})</span>
                    <span className="tabular-nums font-medium text-[#1C1917]">
                      {deliveryFee === 0 ? 'Free' : `৳${deliveryFee}`}
                    </span>
                  </div>
                </div>
                <button
                  type="button"
                  onClick={() => setStep('checkout')}
                  className="w-full py-3 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
                >
                  Proceed to Checkout · ৳{total.toLocaleString()}
                </button>
              </div>
            )}
          </>
        )}

        {step === 'checkout' && (
          <form onSubmit={handlePlaceOrder} className="flex-1 overflow-y-auto p-6 space-y-4">
            <div className="p-3 bg-[#F3F1EC] rounded-lg text-xs text-[#44403C]">
              Visual preview of WooCommerce Checkout. In production (Phase 4), this submits directly to your WooCommerce checkout endpoint.
            </div>
            <div>
              <label className="block text-xs font-semibold text-[#44403C] mb-1">Full Name</label>
              <input
                type="text"
                required
                value={customerName}
                onChange={(e) => setCustomerName(e.target.value)}
                placeholder="e.g. Tanvir Ahmed"
                className="w-full h-10 px-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
              />
            </div>
            <div className="grid grid-cols-2 gap-3">
              <div>
                <label className="block text-xs font-semibold text-[#44403C] mb-1">Mobile Number</label>
                <input
                  type="tel"
                  required
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  placeholder="018XX-XXXXXX"
                  className="w-full h-10 px-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
                />
              </div>
              <div>
                <label className="block text-xs font-semibold text-[#44403C] mb-1">District</label>
                <select
                  value={district}
                  onChange={(e) => setDistrict(e.target.value)}
                  className="w-full h-10 px-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
                >
                  <option value="Dhaka">Dhaka Metro</option>
                  <option value="Chattogram">Chattogram</option>
                  <option value="Sylhet">Sylhet</option>
                  <option value="Rajshahi">Rajshahi</option>
                  <option value="Khulna">Khulna</option>
                </select>
              </div>
            </div>
            <div>
              <label className="block text-xs font-semibold text-[#44403C] mb-1">Delivery Address</label>
              <textarea
                required
                rows={2}
                value={address}
                onChange={(e) => setAddress(e.target.value)}
                placeholder="House, Road, Area / Thana"
                className="w-full p-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
              />
            </div>
            <div>
              <span className="block text-xs font-semibold text-[#44403C] mb-2">Payment Method</span>
              <div className="grid grid-cols-3 gap-2">
                {[
                  { id: 'cod', label: 'Cash on Delivery' },
                  { id: 'bkash', label: 'bKash' },
                  { id: 'nagad', label: 'Nagad' },
                ].map((opt) => (
                  <button
                    key={opt.id}
                    type="button"
                    onClick={() => setPaymentMethod(opt.id as 'cod' | 'bkash' | 'nagad')}
                    className={`py-2.5 px-2 text-xs font-semibold rounded-lg border transition-colors cursor-pointer ${
                      paymentMethod === opt.id
                        ? 'bg-[#0F4C3A] text-white border-[#0F4C3A]'
                        : 'bg-white text-[#1C1917] border-[#E5E2DC] hover:bg-[#F3F1EC]'
                    }`}
                  >
                    {opt.label}
                  </button>
                ))}
              </div>
            </div>

            <div className="pt-4 border-t border-[#E5E2DC] space-y-2 text-sm">
              <div className="flex justify-between font-semibold text-[#1C1917]">
                <span>Total Payable</span>
                <span className="tabular-nums">৳{total.toLocaleString()}</span>
              </div>
              <div className="flex gap-2 pt-2">
                <button
                  type="button"
                  onClick={() => setStep('cart')}
                  className="px-4 py-2.5 text-sm font-medium border border-[#E5E2DC] bg-white rounded-lg hover:bg-[#F3F1EC] cursor-pointer"
                >
                  Back
                </button>
                <button
                  type="submit"
                  className="flex-1 py-2.5 px-4 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
                >
                  Confirm Preview Order
                </button>
              </div>
            </div>
          </form>
        )}

        {step === 'confirmed' && (
          <div className="flex-1 p-6 flex flex-col items-center justify-center text-center">
            <CheckCircle2 className="w-12 h-12 text-[#0F4C3A] mb-4" strokeWidth={1.5} />
            <h3 className="font-display text-2xl font-semibold text-[#1C1917]">
              Order {orderNumber} Confirmed
            </h3>
            <p className="text-sm text-[#57534E] mt-2 max-w-xs leading-relaxed">
              Thank you, {customerName || 'Guest'}. Preparing shipment preview for {district} via{' '}
              {paymentMethod === 'cod' ? 'Cash on Delivery' : paymentMethod.toUpperCase()}.
            </p>
            <button
              type="button"
              onClick={onClose}
              className="mt-6 px-6 py-2.5 bg-[#0F4C3A] text-white text-sm font-semibold rounded-lg hover:bg-[#0A3629] transition-colors cursor-pointer"
            >
              Continue Shopping
            </button>
          </div>
        )}
      </aside>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/* 2. Wishlist Drawer                                                         */
/* -------------------------------------------------------------------------- */
interface WishlistDrawerProps {
  open: boolean;
  onClose: () => void;
  products: StoreProduct[];
  onRemove: (productId: string) => void;
  onMoveToCart: (product: StoreProduct) => void;
}

export function WishlistDrawer({
  open,
  onClose,
  products,
  onRemove,
  onMoveToCart,
}: WishlistDrawerProps) {
  if (!open) return null;

  return (
    <div className="fixed inset-0 z-50 flex justify-end">
      <div className="fixed inset-0 bg-black/45" onClick={onClose} />
      <aside aria-label="Wishlist" className="relative w-full max-w-md bg-[#FAF9F6] h-full shadow-2xl flex flex-col z-10">
        <div className="px-6 py-4 border-b border-[#E5E2DC] bg-white flex items-center justify-between">
          <div>
            <h2 className="font-display text-2xl font-semibold text-[#1C1917]">Saved Wishlist</h2>
            <p className="text-xs text-[#78716C]">{products.length} saved items</p>
          </div>
          <button
            type="button"
            onClick={onClose}
            aria-label="Close wishlist"
            className="p-2 text-[#1C1917] hover:bg-[#EFECE5] rounded-lg cursor-pointer"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        <div className="flex-1 overflow-y-auto p-6 space-y-4">
          {products.length === 0 ? (
            <div className="h-full flex flex-col items-center justify-center text-center py-12">
              <Heart className="w-10 h-10 text-[#A8A29E] mb-3" strokeWidth={1.5} />
              <p className="text-base font-semibold text-[#1C1917]">No saved items yet</p>
              <p className="text-sm text-[#78716C] mt-1 max-w-xs">
                Tap the heart icon on any product to bookmark it for later.
              </p>
            </div>
          ) : (
            products.map((product) => (
              <div key={product.id} className="flex gap-4 bg-white p-3.5 rounded-xl border border-[#E5E2DC]">
                <div className="w-20 h-20 rounded-lg overflow-hidden bg-[#F3F1EC] shrink-0">
                  <SmartImage src={product.image} alt={product.name} className="w-full h-full object-cover" />
                </div>
                <div className="flex-1 min-w-0 flex flex-col justify-between">
                  <div>
                    <h3 className="text-sm font-semibold text-[#1C1917] line-clamp-1">{product.name}</h3>
                    <p className="text-xs text-[#0F4C3A] font-semibold tabular-nums mt-0.5">
                      ৳{(product.salePrice ?? product.regularPrice).toLocaleString()}
                    </p>
                  </div>
                  <div className="flex items-center gap-2 mt-2">
                    <button
                      type="button"
                      onClick={() => onMoveToCart(product)}
                      className="px-3 py-1.5 bg-[#0F4C3A] text-white text-xs font-semibold rounded-lg hover:bg-[#0A3629] cursor-pointer"
                    >
                      Move to Bag
                    </button>
                    <button
                      type="button"
                      onClick={() => onRemove(product.id)}
                      className="px-2.5 py-1.5 text-xs text-[#57534E] hover:text-red-700 cursor-pointer"
                    >
                      Remove
                    </button>
                  </div>
                </div>
              </div>
            ))
          )}
        </div>
      </aside>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/* 3. Product Quick-View / Single Product Page (PDP) Preview Modal            */
/* -------------------------------------------------------------------------- */
interface ProductDetailModalProps {
  product: StoreProduct | null;
  onClose: () => void;
  onAddToCart: (product: StoreProduct, qty: number, variant?: string) => void;
  onBuyNow: (product: StoreProduct, qty: number, variant?: string) => void;
}

export function ProductDetailModal({
  product,
  onClose,
  onAddToCart,
  onBuyNow,
}: ProductDetailModalProps) {
  const [activeImageIdx, setActiveImageIdx] = useState(0);
  const [quantity, setQuantity] = useState(1);
  const [selectedVariant, setSelectedVariant] = useState<string | undefined>(undefined);
  const [activeTab, setActiveTab] = useState<'description' | 'specs' | 'reviews'>('description');

  useEffect(() => {
    if (product) {
      setActiveImageIdx(0);
      setQuantity(1);
      setSelectedVariant(product.variant?.options[0]);
      setActiveTab('description');
    }
  }, [product]);

  if (!product) return null;

  const effectivePrice = product.salePrice ?? product.regularPrice;
  const discountPct = product.salePrice
    ? Math.round(((product.regularPrice - product.salePrice) / product.regularPrice) * 100)
    : null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
      <div className="fixed inset-0 bg-black/50" onClick={onClose} />
      <div
        role="dialog"
        aria-modal="true"
        aria-label={product.name}
        className="relative bg-[#FAF9F6] w-full max-w-4xl max-h-[90vh] rounded-2xl shadow-2xl overflow-y-auto z-10 border border-[#E5E2DC]"
      >
        <button
          type="button"
          onClick={onClose}
          aria-label="Close product preview"
          className="absolute top-4 right-4 z-20 w-10 h-10 bg-white/95 hover:bg-white rounded-lg border border-[#E5E2DC] flex items-center justify-center text-[#1C1917] cursor-pointer"
        >
          <X className="w-5 h-5" />
        </button>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 p-6 sm:p-8">
          {/* Gallery */}
          <div className="space-y-3">
            <div className="aspect-[4/3] bg-[#F3F1EC] rounded-xl overflow-hidden border border-[#E5E2DC]">
              <SmartImage
                src={product.gallery[activeImageIdx] || product.image}
                alt={product.name}
                className="w-full h-full object-cover"
                eager
              />
            </div>
            {product.gallery.length > 1 && (
              <div className="flex gap-2.5">
                {product.gallery.map((img, idx) => (
                  <button
                    key={img + idx}
                    type="button"
                    onClick={() => setActiveImageIdx(idx)}
                    className={`w-20 aspect-[4/3] rounded-lg overflow-hidden border-2 transition-colors cursor-pointer ${
                      activeImageIdx === idx ? 'border-[#0F4C3A]' : 'border-transparent opacity-70 hover:opacity-100'
                    }`}
                  >
                    <SmartImage src={img} alt={`${product.name} view ${idx + 1}`} className="w-full h-full object-cover" />
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Purchase Module */}
          <div className="flex flex-col justify-between space-y-5">
            <div>
              <div className="flex items-center gap-2 text-xs text-[#78716C] mb-2">
                <span>{product.category}</span>
                <span aria-hidden="true">·</span>
                <span>SKU: {product.sku}</span>
                <span aria-hidden="true">·</span>
                <span className="text-[#0F4C3A] font-semibold">
                  {product.stockStatus} ({product.stockCount} available)
                </span>
              </div>

              <h2 className="font-display text-2xl sm:text-3xl font-semibold text-[#1C1917] leading-tight">
                {product.name}
              </h2>

              <div className="flex items-center gap-2 mt-2 text-xs text-[#44403C]">
                <div className="flex items-center gap-0.5 text-[#B45309]">
                  <Star className="w-3.5 h-3.5 fill-current" />
                  <span className="font-semibold text-[#1C1917] ml-1 tabular-nums">{product.rating.toFixed(1)}</span>
                </div>
                <span aria-hidden="true">·</span>
                <span className="tabular-nums">{product.reviewCount} verified reviews</span>
              </div>

              <div className="mt-4 flex items-baseline gap-3 tabular-nums">
                <span className="text-2xl font-bold text-[#1C1917]">
                  ৳{effectivePrice.toLocaleString()}
                </span>
                {product.salePrice && (
                  <>
                    <span className="text-base text-[#78716C] line-through">
                      ৳{product.regularPrice.toLocaleString()}
                    </span>
                    <span className="text-xs font-semibold text-[#0F4C3A]">
                      Save {discountPct}%
                    </span>
                  </>
                )}
              </div>

              <p className="mt-3 text-sm text-[#44403C] leading-relaxed">
                {product.shortDescription}
              </p>

              {/* Variation Selector */}
              {product.variant && (
                <div className="mt-5">
                  <span className="block text-xs font-semibold text-[#1C1917] mb-2">
                    {product.variant.label}: {selectedVariant}
                  </span>
                  <div className="flex flex-wrap gap-2">
                    {product.variant.options.map((opt) => (
                      <button
                        key={opt}
                        type="button"
                        onClick={() => setSelectedVariant(opt)}
                        className={`px-3.5 py-2 text-xs font-medium rounded-lg border transition-colors cursor-pointer ${
                          selectedVariant === opt
                            ? 'bg-[#1C1917] text-white border-[#1C1917]'
                            : 'bg-white text-[#1C1917] border-[#E5E2DC] hover:bg-[#F3F1EC]'
                        }`}
                      >
                        {opt}
                      </button>
                    ))}
                  </div>
                </div>
              )}

              {/* Quantity & CTAs */}
              <div className="mt-6 flex flex-wrap items-center gap-3">
                <div className="inline-flex items-center border border-[#E5E2DC] rounded-lg bg-white h-11">
                  <button
                    type="button"
                    onClick={() => setQuantity((q) => Math.max(1, q - 1))}
                    aria-label="Decrease quantity"
                    className="w-10 h-full flex items-center justify-center hover:bg-[#F3F1EC] cursor-pointer"
                  >
                    <Minus className="w-3.5 h-3.5" />
                  </button>
                  <span className="w-9 text-center text-sm font-semibold tabular-nums">{quantity}</span>
                  <button
                    type="button"
                    onClick={() => setQuantity((q) => Math.min(product.stockCount, q + 1))}
                    aria-label="Increase quantity"
                    className="w-10 h-full flex items-center justify-center hover:bg-[#F3F1EC] cursor-pointer"
                  >
                    <Plus className="w-3.5 h-3.5" />
                  </button>
                </div>

                <button
                  type="button"
                  onClick={() => {
                    onAddToCart(product, quantity, selectedVariant);
                    onClose();
                  }}
                  className="flex-1 h-11 px-5 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer"
                >
                  Add to Bag
                </button>

                <button
                  type="button"
                  onClick={() => {
                    onBuyNow(product, quantity, selectedVariant);
                    onClose();
                  }}
                  className="h-11 px-5 bg-[#1C1917] hover:bg-[#292524] text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap cursor-pointer"
                >
                  Buy Now
                </button>
              </div>
            </div>

            {/* Product Information Tabs */}
            <div className="pt-5 border-t border-[#E5E2DC]">
              <div className="flex gap-5 border-b border-[#E5E2DC] text-xs font-semibold">
                {(['description', 'specs', 'reviews'] as const).map((tab) => (
                  <button
                    key={tab}
                    type="button"
                    onClick={() => setActiveTab(tab)}
                    className={`pb-2.5 capitalize border-b-2 transition-colors cursor-pointer ${
                      activeTab === tab
                        ? 'border-[#0F4C3A] text-[#0F4C3A]'
                        : 'border-transparent text-[#78716C] hover:text-[#1C1917]'
                    }`}
                  >
                    {tab === 'specs' ? 'Additional Info' : tab}
                  </button>
                ))}
              </div>
              <div className="pt-3.5 text-xs text-[#44403C] leading-relaxed">
                {activeTab === 'description' && <p>{product.description}</p>}
                {activeTab === 'specs' && (
                  <dl className="space-y-1.5">
                    {product.specs.map((s) => (
                      <div key={s.label} className="flex justify-between gap-4 py-1 border-b border-[#F3F1EC] last:border-0">
                        <dt className="font-semibold text-[#1C1917]">{s.label}</dt>
                        <dd className="text-right text-[#57534E]">{s.value}</dd>
                      </div>
                    ))}
                  </dl>
                )}
                {activeTab === 'reviews' && (
                  <div className="space-y-2.5">
                    {product.reviews.length === 0 ? (
                      <p>Customer reviews load dynamically from WooCommerce comments in Phase 4.</p>
                    ) : (
                      product.reviews.map((rev) => (
                        <div key={rev.id} className="bg-white p-3 rounded-lg border border-[#E5E2DC]">
                          <div className="flex justify-between items-center mb-1">
                            <span className="font-semibold text-[#1C1917]">
                              {rev.author} · {rev.district}
                            </span>
                            <span className="text-[#78716C]">{rev.date}</span>
                          </div>
                          <p>{rev.comment}</p>
                        </div>
                      ))
                    )}
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

/* -------------------------------------------------------------------------- */
/* 4. My Account / Login Preview Modal                                        */
/* -------------------------------------------------------------------------- */
interface AccountModalProps {
  open: boolean;
  onClose: () => void;
}

export function AccountModal({ open, onClose }: AccountModalProps) {
  const [tab, setTab] = useState<'login' | 'register' | 'orders'>('login');
  const [statusMessage, setStatusMessage] = useState<string | null>(null);

  if (!open) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div className="fixed inset-0 bg-black/50" onClick={onClose} />
      <div
        role="dialog"
        aria-modal="true"
        aria-label="My Account Preview"
        className="relative bg-[#FAF9F6] w-full max-w-md rounded-2xl shadow-2xl border border-[#E5E2DC] p-6 z-10"
      >
        <div className="flex items-center justify-between mb-4">
          <div>
            <h2 className="font-display text-2xl font-semibold text-[#1C1917]">My Account</h2>
            <p className="text-xs text-[#78716C]">WooCommerce Customer Portal UI Preview</p>
          </div>
          <button
            type="button"
            onClick={onClose}
            aria-label="Close account modal"
            className="p-2 text-[#1C1917] hover:bg-[#EFECE5] rounded-lg cursor-pointer"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        <div className="flex gap-1 p-1 bg-[#EFECE5] rounded-lg mb-5">
          {(['login', 'register', 'orders'] as const).map((t) => (
            <button
              key={t}
              type="button"
              onClick={() => {
                setTab(t);
                setStatusMessage(null);
              }}
              className={`flex-1 py-1.5 text-xs font-semibold rounded-md capitalize transition-colors cursor-pointer ${
                tab === t ? 'bg-white text-[#1C1917] shadow-sm' : 'text-[#57534E] hover:text-[#1C1917]'
              }`}
            >
              {t === 'orders' ? 'Order History' : t}
            </button>
          ))}
        </div>

        {statusMessage && (
          <div className="mb-4 p-3 bg-[#0F4C3A]/10 text-[#0F4C3A] text-xs font-medium rounded-lg">
            {statusMessage}
          </div>
        )}

        {tab === 'login' && (
          <form
            onSubmit={(e) => {
              e.preventDefault();
              setStatusMessage('UI preview verified — connects to wp_signon() / WooCommerce My Account in Phase 4.');
            }}
            className="space-y-3.5"
          >
            <div>
              <label className="block text-xs font-semibold text-[#44403C] mb-1">Email or Phone</label>
              <input
                type="text"
                required
                placeholder="customer@example.com"
                className="w-full h-10 px-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-[#44403C] mb-1">Password</label>
              <input
                type="password"
                required
                placeholder="••••••••"
                className="w-full h-10 px-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
              />
            </div>
            <button
              type="submit"
              className="w-full h-10 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
            >
              Sign In
            </button>
          </form>
        )}

        {tab === 'register' && (
          <form
            onSubmit={(e) => {
              e.preventDefault();
              setStatusMessage('Registration preview — maps to WooCommerce customer registration nonce in Phase 4.');
            }}
            className="space-y-3.5"
          >
            <div>
              <label className="block text-xs font-semibold text-[#44403C] mb-1">Full Name</label>
              <input
                type="text"
                required
                placeholder="Your full name"
                className="w-full h-10 px-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
              />
            </div>
            <div>
              <label className="block text-xs font-semibold text-[#44403C] mb-1">Email Address</label>
              <input
                type="email"
                required
                placeholder="you@example.com"
                className="w-full h-10 px-3 text-sm bg-white border border-[#E5E2DC] rounded-lg focus:outline-none focus:border-[#0F4C3A]"
              />
            </div>
            <button
              type="submit"
              className="w-full h-10 bg-[#0F4C3A] hover:bg-[#0A3629] text-white text-sm font-semibold rounded-lg transition-colors cursor-pointer"
            >
              Create Account
            </button>
          </form>
        )}

        {tab === 'orders' && (
          <div className="space-y-3">
            <div className="bg-white p-3.5 rounded-xl border border-[#E5E2DC] flex items-center justify-between">
              <div>
                <p className="text-sm font-semibold text-[#1C1917] tabular-nums">Order #1042</p>
                <p className="text-xs text-[#78716C]">Nov 15, 2026 · Delivered (Dhaka)</p>
              </div>
              <span className="text-sm font-semibold text-[#0F4C3A] tabular-nums">৳10,450</span>
            </div>
            <div className="bg-white p-3.5 rounded-xl border border-[#E5E2DC] flex items-center justify-between">
              <div>
                <p className="text-sm font-semibold text-[#1C1917] tabular-nums">Order #1028</p>
                <p className="text-xs text-[#78716C]">Oct 24, 2026 · Completed</p>
              </div>
              <span className="text-sm font-semibold text-[#0F4C3A] tabular-nums">৳2,390</span>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
