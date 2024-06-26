{{--
    ? This is the basket page
    ? It is used to display the products in the basket and allows the customer to:
    ?   Increment/decrement the quantity of the product
    ?   Remove the product from the basket
    ?   Proceed to Checkout
--}}

<x-app-layout>

    <!-- Header Container -->
    <x-slot name="header">
        Basket
    </x-slot>

    <!-- Main Container -->
    <div class="flex flex-col items-center mt-4 space-y-8 md:space-x-4 md:flex-row md:justify-center">
        @if ($basketItems)
            <!-- Basket items container -->
            <div
                class="w-full gap-10 md:w-[1037px] p-3 sm:p-10 border-2 border-navy-blue bg-white bg-opacity-40 backdrop-blur-sm">
                {{-- * Loops through all basket items in the user's basket --}}

                @foreach ($basketItems as $item)
                    {{-- * The basket item component is generated --}}
                    <x-basket-item>
                        <x-slot name="image">
                            {{ $item->product->image }} {{-- * The product image is placed in the image placeholder --}}
                        </x-slot>
                        <x-slot name="productName">
                            {{ $item->product->name }} {{-- * The product name is placed in the title placeholder --}}
                        </x-slot>
                        <x-slot name="size">
                            {{ $item->variation->size }} {{-- * The size of the product is placed in the size placeholder --}}
                        </x-slot>
                        {{-- * The counter is placed in the counter placeholder --}}
                        <x-slot name="counter">
                            {{-- * This is the form that allows the button inside the form perform a post request that decrements the quantity
                             * of the basket item inside the user's basket --}}
                        <form action="{{ route('decrementQuantity', ['productId' => $item->product->id, 'variationId' => $item->variation->id]) }}"
                            method="post">
                            @csrf
                            {{-- * This is a button that allows for decrementing the quantity --}}
                            <button
                                class="mr-3 w-8 h-8 font-bold text-black bg-transparent border border-black squared">-</button>
                        </form>
                        {{-- * The quantity of the basket item is fetched from the basket item record in the basket items table
                             * It is updated whenever the buttons for incrementing and decrementing the quantity are clicked --}}
                            <span class="flex items-center font-bold">{{ $item->quantity }}</span>
                            {{-- * This is the form that allows the button inside the form perform a post request that increments the quantity
                             * of the basket item inside the user's basket --}}
                        <form action="{{ route('incrementQuantity', ['productId' => $item->product->id, 'variationId' => $item->variation->id]) }}"
                            method="post">
                            @csrf
                            {{-- * This is a button that allows for incrementing the quantity --}}
                            <button
                                class="ml-3 w-8 h-8 font-bold text-black bg-transparent border border-black squared">+</button>
                        </form>
                    </x-slot>

                        {{-- * The price of the basket item is placed in the price placeholder --}}
                        <x-slot name="price">
                            {{ $item->product->selling_price }}
                        </x-slot>

                        {{-- * The item removal block is placed inside the remove placeholder --}}
                        <x-slot name="remove">
                            {{-- * This is the form that allows the button inside the form to perform a post request that performs the removal of
                             * the basket item from the basket. It removes the basket item from the basket completely --}}
                            <form action="{{ route('basket.remove', $item->product->id) }}" method="POST">
                                @csrf
                                @method('DELETE') {{-- * This makes sure the appropriate button below deletes the record in the basket items table --}}
                                <div x-data="{ showRemoveText: window.innerWidth > 768 }" x-init="() => { window.addEventListener('resize', () => { showRemoveText = window.innerWidth > 768 }); }">
                                    <button type="submit" class="text-red" x-show="showRemoveText">Remove</button>
                                    {{-- * This button is only visible on larger screens. It is a --}}
                                    <button type="submit" class="text-2xl text-red font-formula1"
                                        x-show="!showRemoveText"><img src="{{ asset('icons/utility/cancel-icon.png') }}"
                                            alt=""></button> {{-- * This is the button that is only visible on mobile phones/small screens. It is a red X button. --}}
                                </div>
                            </form>
                        </x-slot>
                    </x-basket-item>
                @endforeach
                <form method="POST" action="{{ route('basket.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="mt-5 w-full text-base text-red f">Clear Basket</button>
                </form>
            </div>

            <!-- Gap between Product Cards and Cart Summary (visible only on larger screens) -->
            <div class="hidden md:w-8 md:block"></div>

            <!-- Cart Summary Container -->
            <div class="w-full md:w-[414px] p-4 bg-white bg-opacity-40 border-2 border-navy-blue backdrop-blur-[18px]">

                <div class = "justify-center self-stretch my-4">
                    <h3 class="text-md font-medium text-black font-formula1">Have a discount code?</h3>
                    @include('basket.partials.discount-code-form')
                </div>

                <!-- Subtotal, Discount, Total -->
                <div class="flex justify-between items-center self-stretch px-4 h-12 border-b-2 border-snow-white">
                    <div class="flex gap-2.5 justify-center items-center px-2 py-2">
                        <div class="text-sm font-medium text-black font-lexend-deca">Subtotal</div>
                    </div>
                    <div class="flex gap-2.5 justify-center items-center px-2 py-2">
                        <div class="text-sm font-medium text-black font-lexend-deca subtotal-value">£{{ $subTotal }}
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center self-stretch px-4 h-12 border-b-2 border-snow-white">
                    <div class="flex gap-2.5 justify-center items-center px-2 py-2">
                        <div class="text-sm font-medium text-black font-lexend-deca">Discount</div>
                    </div>
                    <div class="flex gap-2.5 justify-center items-center px-2 py-2">
                        <div class="text-sm font-medium text-black font-lexend-deca">£{{ $discountAmount }}</div>
                    </div>
                </div>
                <div class="flex justify-between items-center self-stretch px-4 h-12">
                    <div class="flex gap-2.5 justify-center items-center px-2 py-2">
                        <div class="text-sm font-bold text-black font-lexend-deca">Total</div>
                    </div>
                    <div class="flex gap-2.5 justify-center items-center px-2 py-2">
                        <div class="text-sm font-bold text-black font-lexend-deca total-value">£{{ $totalAmount }}</div>
                    </div>
                </div>

                <!-- Checkout Button -->
                <div class="justify-center self-stretch mt-4">
                    <a href="{{ route('checkout') }}">
                        <x-primary-button class="mt-5 w-full">Checkout</x-primary-button>
                    </a>
                </div>
            </div>
    </div>
    @endif
</x-app-layout>
