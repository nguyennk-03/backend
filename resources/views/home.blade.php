@extends('layouts.app')
@section('title', 'Trang chủ')
@section('content')
  <main>
    <article>
    <!-- Hero Section (Static for now) -->
    <section class="section hero" style="background-image: url('{{ asset('images/hero-banner.png') }}')">
      <div class="container">
      <h2 class="h1 hero-title">
        New Summer <strong>Shoes Collection</strong>
      </h2>
      <p class="hero-text">
        Competently expedite alternative benefits whereas leading-edge catalysts for change.
      </p>
      <button class="btn btn-primary">
        <span>Shop Now</span>
        <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
      </button>
      </div>
    </section>

    <!-- Collection Section -->
    <section class="section collection">
      <div class="container">
      <ul class="collection-list has-scrollbar">
        @foreach($categories as $category)
      <li>
      <div class="collection-card" style="background-image: url('{{ asset($category->image_url) }}')">
        <h3 class="h4 card-title">{{ $category->name }} Collections</h3>
        <a href="{{ route('category.show', $category->slug) }}" class="btn btn-secondary">
        <span>Explore All</span>
        <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
        </a>
      </div>
      </li>
    @endforeach
      </ul>
      </div>
    </section>

    <!-- Product Section -->
    <section class="section product">
      <div class="container">
      <h2 class="h2 section-title">Bestsellers Products</h2>
      <ul class="filter-list">
        <li><button class="filter-btn active">All</button></li>
        @foreach($brands as $brand)
      <li><button class="filter-btn">{{ $brand->name }}</button></li>
    @endforeach
      </ul>
      <ul class="product-list">
        @foreach($bestsellers as $product)
        @php
        $variant = $product->variants->first(); // Get the first variant
        $image = $variant->images->first()->image_url ?? asset('images/default-product.jpg');
        $price = $variant->price;
        $oldPrice = null; // Add logic for discounts if applicable
      @endphp
        <li class="product-item">
        <div class="product-card" tabindex="0">
          <figure class="card-banner">
          <img src="{{ $image }}" width="312" height="350" loading="lazy" alt="{{ $product->name }}"
          class="image-contain">
          @if($product->variants->sum('stock') == 0)
        <div class="card-badge">Out of Stock</div>
      @elseif(now()->between($variant->created_at, $variant->created_at->addDays(7)))
      <div class="card-badge">New</div>
    @endif
          <ul class="card-action-list">
          <li class="card-action-item">
          <button class="card-action-btn" aria-label="Add to Cart">
            <ion-icon name="cart-outline"></ion-icon>
          </button>
          <div class="card-action-tooltip" id="card-label-cart-{{ $loop->index }}">Add to Cart</div>
          </li>
          <li class="card-action-item">
          <button class="card-action-btn" aria-label="Add to Wishlist">
            <ion-icon name="heart-outline"></ion-icon>
          </button>
          <div class="card-action-tooltip" id="card-label-wishlist-{{ $loop->index }}">Add to Wishlist</div>
          </li>
          <li class="card-action-item">
          <button class="card-action-btn" aria-label="Quick View">
            <ion-icon name="eye-outline"></ion-icon>
          </button>
          <div class="card-action-tooltip" id="card-label-view-{{ $loop->index }}">Quick View</div>
          </li>
          <li class="card-action-item">
          <button class="card-action-btn" aria-label="Compare">
            <ion-icon name="repeat-outline"></ion-icon>
          </button>
          <div class="card-action-tooltip" id="card-label-compare-{{ $loop->index }}">Compare</div>
          </li>
          </ul>
          </figure>
          <div class="card-content">
          <div class="card-cat">
          <a href="{{ route('category.show', $product->category->slug) }}"
          class="card-cat-link">{{ $product->category->name }}</a> /
          <a href="{{ route('brand.show', $product->brand->slug) }}"
          class="card-cat-link">{{ $product->brand->name }}</a>
          </div>
          <h3 class="h3 card-title">
          <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
          </h3>
          <data class="card-price" value="{{ $price }}">${{ number_format($price, 2) }}
          @if($oldPrice)
        <del>${{ number_format($oldPrice, 2) }}</del>
      @endif
          </data>
          </div>
        </div>
        </li>
    @endforeach
      </ul>
      </div>
    </section>

    <!-- CTA Section (Static for now) -->
    <section class="section cta">
      <div class="container">
      <ul class="cta-list">
        <li>
        <div class="cta-card" style="background-image: url('{{ asset('images/cta-1.jpg') }}')">
          <p class="card-subtitle">Adidas Shoes</p>
          <h3 class="h2 card-title">The Summer Sale Off 50%</h3>
          <a href="#" class="btn btn-link">
          <span>Shop Now</span>
          <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
          </a>
        </div>
        </li>
        <li>
        <div class="cta-card" style="background-image: url('{{ asset('images/cta-2.jpg') }}')">
          <p class="card-subtitle">Nike Shoes</p>
          <h3 class="h2 card-title">Makes Yourself Keep Sporty</h3>
          <a href="#" class="btn btn-link">
          <span>Shop Now</span>
          <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
          </a>
        </div>
        </li>
      </ul>
      </div>
    </section>

    <!-- Special Section -->
    <section class="section special">
      <div class="container">
      <div class="special-banner" style="background-image: url('{{ asset('images/special-banner.jpg') }}')">
        <h2 class="h3 banner-title">New Trend Edition</h2>
        <a href="#" class="btn btn-link">
        <span>Explore All</span>
        <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
        </a>
      </div>
      <div class="special-product">
        <h2 class="h2 section-title">
        <span class="text">Nike Special</span>
        <span class="line"></span>
        </h2>
        <ul class="has-scrollbar">
        @foreach($specialProducts as $product)
        @php
        $variant = $product->variants->first();
        $image = $variant->images->first()->image_url ?? asset('images/default-product.jpg');
        $price = $variant->price;
    @endphp
        <li class="product-item">
          <div class="product-card" tabindex="0">
          <figure class="card-banner">
          <img src="{{ $image }}" width="312" height="350" loading="lazy" alt="{{ $product->name }}"
          class="image-contain">
          @if($product->variants->sum('stock') == 0)
        <div class="card-badge">Out of Stock</div>
      @elseif(now()->between($variant->created_at, $variant->created_at->addDays(7)))
      <div class="card-badge">New</div>
    @endif
          <ul class="card-action-list">
          <li class="card-action-item">
            <button class="card-action-btn" aria-label="Add to Cart">
            <ion-icon name="cart-outline"></ion-icon>
            </button>
            <div class="card-action-tooltip" id="special-card-label-cart-{{ $loop->index }}">Add to Cart</div>
          </li>
          <li class="card-action-item">
            <button class="card-action-btn" aria-label="Add to Wishlist">
            <ion-icon name="heart-outline"></ion-icon>
            </button>
            <div class="card-action-tooltip" id="special-card-label-wishlist-{{ $loop->index }}">Add to
            Wishlist</div>
          </li>
          <li class="card-action-item">
            <button class="card-action-btn" aria-label="Quick View">
            <ion-icon name="eye-outline"></ion-icon>
            </button>
            <div class="card-action-tooltip" id="special-card-label-view-{{ $loop->index }}">Quick View</div>
          </li>
          <li class="card-action-item">
            <button class="card-action-btn" aria-label="Compare">
            <ion-icon name="repeat-outline"></ion-icon>
            </button>
            <div class="card-action-tooltip" id="special-card-label-compare-{{ $loop->index }}">Compare</div>
          </li>
          </ul>
          </figure>
          <div class="card-content">
          <div class="card-cat">
          <a href="{{ route('category.show', $product->category->slug) }}"
            class="card-cat-link">{{ $product->category->name }}</a> /
          <a href="{{ route('brand.show', $product->brand->slug) }}"
            class="card-cat-link">{{ $product->brand->name }}</a>
          </div>
          <h3 class="h3 card-title">
          <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
          </h3>
          <data class="card-price" value="{{ $price }}">${{ number_format($price, 2) }}</data>
          </div>
          </div>
        </li>
    @endforeach
        </ul>
      </div>
      </div>
    </section>

    <!-- Service Section (Static) -->
    <section class="section service">
      <div class="container">
      <ul class="service-list">
        <li class="service-item">
        <div class="service-card">
          <div class="card-icon">
          <img src="{{ asset('images/service-1.png') }}" width="53" height="28" loading="lazy"
            alt="Free Shipping Icon">
          </div>
          <div>
          <h3 class="h4 card-title">Free Shipping</h3>
          <p class="card-text">All orders over <span>$150</span></p>
          </div>
        </div>
        </li>
        <li class="service-item">
        <div class="service-card">
          <div class="card-icon">
          <img src="{{ asset('images/service-2.png') }}" width="43" height="35" loading="lazy"
            alt="Quick Payment Icon">
          </div>
          <div>
          <h3 class="h4 card-title">Quick Payment</h3>
          <p class="card-text">100% secure payment</p>
          </div>
        </div>
        </li>
        <li class="service-item">
        <div class="service-card">
          <div class="card-icon">
          <img src="{{ asset('images/service-3.png') }}" width="40" height="40" loading="lazy"
            alt="Free Returns Icon">
          </div>
          <div>
          <h3 class="h4 card-title">Free Returns</h3>
          <p class="card-text">Money back in 30 days</p>
          </div>
        </div>
        </li>
        <li class="service-item">
        <div class="service-card">
          <div class="card-icon">
          <img src="{{ asset('images/service-4.png') }}" width="40" height="40" loading="lazy"
            alt="24/7 Support Icon">
          </div>
          <div>
          <h3 class="h4 card-title">24/7 Support</h3>
          <p class="card-text">Get Quick Support</p>
          </div>
        </div>
        </li>
      </ul>
      </div>
    </section>

    <!-- Instagram Post Section (Static) -->
    <section class="section insta-post">
      <ul class="insta-post-list has-scrollbar">
      @foreach(range(1, 8) as $i)
      <li class="insta-post-item">
      <img src="{{ asset('images/insta-' . $i . '.jpg') }}" width="100" height="100" loading="lazy"
      alt="Instagram post" class="insta-post-banner image-contain">
      <a href="#" class="insta-post-link">
      <ion-icon name="logo-instagram"></ion-icon>
      </a>
      </li>
    @endforeach
      </ul>
    </section>
    </article>
  </main>
@endsection