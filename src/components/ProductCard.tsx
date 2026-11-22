import { Heart, ShoppingBag, Star } from 'lucide-react';
import { StoreProduct } from '../types/store';
import { SmartImage } from './SmartImage';

interface ProductCardProps {
  product: StoreProduct;
  isWishlisted: boolean;
  onToggleWishlist: (productId: string) => void;
  onAddToCart: (product: StoreProduct) => void;
  onOpenDetail: (product: StoreProduct) => void;
}

export function ProductCard({
  product,
  isWishlisted,
  onToggleWishlist,
  onAddToCart,
  onOpenDetail,
}: ProductCardProps) {
  const effectivePrice = product.salePrice ?? product.regularPrice;
  const discountPercent = product.salePrice
    ? Math.round(((product.regularPrice - product.salePrice) / product.regularPrice) * 100)
    : null;

  return (
    <article className="group flex flex-col bg-white border border-[#E5E2DC] rounded-xl overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
      {/* Studio Image Frame */}
      <div className="relative aspect-[4/3] bg-[#F3F1EC] overflow-hidden">
        <button
          type="button"
          onClick={() => onOpenDetail(product)}
          aria-label={`View details for ${product.name}`}
          className="w-full h-full block text-left cursor-pointer"
        >
          <SmartImage
            src={product.image}
            alt={product.name}
            className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]"
          />
        </button>

        {/* Single subtle unboxed status label */}
        {(discountPercent || product.stockStatus === 'Low Stock') && (
          <span className="absolute top-3 left-3 bg-white/95 text-[#0F4C3A] text-[11px] font-semibold px-2.5 py-1 rounded tracking-wide">
            {discountPercent ? `Save ${discountPercent}%` : 'Low Stock'}
          </span>
        )}

        {/* Wishlist Action */}
        <button
          type="button"
          onClick={() => onToggleWishlist(product.id)}
          aria-label={isWishlisted ? `Remove ${product.name} from wishlist` : `Save ${product.name} to wishlist`}
          className={`absolute top-3 right-3 w-9 h-9 rounded-lg flex items-center justify-center transition-colors cursor-pointer ${
            isWishlisted
              ? 'bg-[#0F4C3A] text-white'
              : 'bg-white/95 text-[#1C1917] hover:bg-white'
          }`}
        >
          <Heart className={`w-4 h-4 ${isWishlisted ? 'fill-current' : ''}`} strokeWidth={1.75} />
        </button>
      </div>

      {/* Product Details */}
      <div className="p-4 sm:p-5 flex-1 flex flex-col justify-between gap-4">
        <div>
          <div className="flex items-center justify-between gap-2 text-xs text-[#78716C] mb-1.5">
            <span className="truncate">
              {product.category} <span aria-hidden="true">·</span> {product.origin}
            </span>
            <span className="flex items-center gap-1 shrink-0 text-[#44403C] tabular-nums">
              <Star className="w-3.5 h-3.5 fill-[#B45309] text-[#B45309]" />
              {product.rating.toFixed(1)}
            </span>
          </div>

          <button
            type="button"
            onClick={() => onOpenDetail(product)}
            className="text-left block w-full cursor-pointer"
          >
            <h3 className="text-[15px] font-semibold text-[#1C1917] leading-snug line-clamp-2 group-hover:text-[#0F4C3A] transition-colors">
              {product.name}
            </h3>
          </button>
        </div>

        <div className="pt-3 border-t border-[#F3F1EC] flex items-center justify-between gap-3">
          <div className="flex items-baseline gap-2 tabular-nums">
            <span className="text-base font-semibold text-[#1C1917]">
              ৳{effectivePrice.toLocaleString()}
            </span>
            {product.salePrice && (
              <span className="text-xs text-[#78716C] line-through">
                ৳{product.regularPrice.toLocaleString()}
              </span>
            )}
          </div>

          <button
            type="button"
            onClick={() => onAddToCart(product)}
            className="inline-flex items-center gap-1.5 px-3 py-2 bg-[#F3F1EC] hover:bg-[#0F4C3A] text-[#1C1917] hover:text-white text-xs font-semibold rounded-lg transition-colors whitespace-nowrap shrink-0 cursor-pointer"
          >
            <ShoppingBag className="w-3.5 h-3.5" strokeWidth={1.75} />
            Add
          </button>
        </div>
      </div>
    </article>
  );
}
