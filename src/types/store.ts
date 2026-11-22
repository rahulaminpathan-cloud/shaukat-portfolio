export type ProductCollectionTag = 'best-selling' | 'featured' | 'latest' | 'discount';

export interface ProductVariant {
  label: string;
  options: string[];
}

export interface ProductReview {
  id: string;
  author: string;
  district: string;
  rating: number;
  date: string;
  comment: string;
  verified: boolean;
}

export interface StoreProduct {
  id: string;
  sku: string;
  name: string;
  category: string;
  categorySlug: string;
  origin: string;
  regularPrice: number;
  salePrice?: number;
  rating: number;
  reviewCount: number;
  stockStatus: 'In Stock' | 'Low Stock';
  stockCount: number;
  image: string;
  gallery: string[];
  collections: ProductCollectionTag[];
  shortDescription: string;
  description: string;
  specs: { label: string; value: string }[];
  variant?: ProductVariant;
  reviews: ProductReview[];
}

export interface StoreSubcategory {
  slug: string;
  name: string;
  itemCount: number;
}

export interface StoreCategory {
  slug: string;
  name: string;
  itemCount: number;
  subtitle: string;
  image: string;
  subcategories?: StoreSubcategory[];
}

export interface HeroSlide {
  id: string;
  kicker: string;
  headline: string;
  subcopy: string;
  ctaLabel: string;
  targetCategory: string;
  image: string;
}

export interface JournalArticle {
  id: string;
  category: string;
  date: string;
  readTime: string;
  title: string;
  excerpt: string;
  image: string;
}

export interface CustomerTestimonial {
  id: string;
  name: string;
  location: string;
  purchasedItem: string;
  rating: number;
  quote: string;
}

export interface CartItem {
  product: StoreProduct;
  quantity: number;
  selectedVariant?: string;
}
