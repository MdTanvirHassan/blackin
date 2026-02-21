@extends('frontend.layouts.app')

@section('content')
    <!-- Cart Details -->
    <section class="my-4" id="cart-details">
        @include('frontend.partials.cart.cart_details', ['carts' => $carts])
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            let currentValue = element.value;
            $('.aiz-refresh').addClass('active');
            
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: currentValue
            }, function(data) {
                $('.aiz-refresh').removeClass('active');
                
                if (data.status === 0 && data.message) {
                    AIZ.plugins.notify('warning', data.message);
                    element.value = currentValue;
                } else {
                    if (data.nav_cart_view) {
                        updateNavCart(data.nav_cart_view, data.cart_count);
                    }
                    if (data.cart_view) {
                        $('#cart-details').html(data.cart_view);
                    }
                    setTimeout(function() {
                        AIZ.extra.plusMinus();
                    }, 100);
                }
            }).fail(function() {
                $('.aiz-refresh').removeClass('active');
                AIZ.plugins.notify('danger', "{{ translate('An error occurred while updating quantity.') }}");
                element.value = currentValue;
            });
        }
        
        // Update bundle quantity (all items in the bundle together)
        // groupKey can be groupId (number) or groupKey (groupId_hash format)
        function updateBundleQuantity(cartItemId, groupKey, type, value = null) {
            // Parse groupKey - it might be a number (groupId) or string (groupId_hash)
            let groupKeyStr = String(groupKey);
            let quantityInputId = 'bundle-quantity-' + groupKeyStr;
            let quantityInput = $('#' + quantityInputId);
            
            if (!quantityInput.length) {
                AIZ.plugins.notify('danger', "{{ translate('Quantity input not found') }}");
                return;
            }
            
            let currentQuantity = parseInt(quantityInput.val()) || 1;
            let newQuantity = currentQuantity;
            
            if (type === 1) {
                newQuantity = currentQuantity + 1;
            } else if (type === -1) {
                newQuantity = Math.max(1, currentQuantity - 1);
            } else if (value !== null && value !== '') {
                newQuantity = parseInt(value) || 1;
            }
            
            let min = parseInt(quantityInput.attr('min')) || 1;
            let max = parseInt(quantityInput.attr('max')) || 999999;
            
            if (newQuantity < min) {
                newQuantity = min;
                AIZ.plugins.notify('warning', "{{ translate('Minimum quantity not satisfied') }}");
                quantityInput.val(newQuantity);
                return;
            }
            if (newQuantity > max) {
                newQuantity = max;
                AIZ.plugins.notify('warning', "{{ translate('Maximum quantity exceeded') }}");
                quantityInput.val(newQuantity);
                return;
            }
            
            if (newQuantity === currentQuantity && type !== 0) {
                return;
            }
            
            // Update input value immediately for better UX
            quantityInput.val(newQuantity);
            
            // Show loading state
            $('.aiz-refresh').addClass('active');
            
            // Make AJAX request to update bundle quantity
            // Pass slot_combination_hash if available in groupKey
            let requestData = {
                _token: AIZ.data.csrf,
                id: cartItemId,
                quantity: newQuantity
            };
            
            // If groupKey contains hash (format: groupId_hash), pass it separately
            if (groupKeyStr.indexOf('_') !== -1) {
                let parts = groupKeyStr.split('_');
                if (parts.length >= 2) {
                    // First part is groupId, rest is hash
                    requestData.slot_combination_hash = parts.slice(1).join('_');
                }
            }
            
            $.post('{{ route('cart.updateQuantity') }}', requestData, function(data) {
                $('.aiz-refresh').removeClass('active');
                
                if (data.status == 1 || data.status === undefined) {
                    // Success - update cart views
                    if (data.nav_cart_view) {
                        updateNavCart(data.nav_cart_view, data.cart_count);
                    }
                    if (data.cart_view) {
                        $('#cart-details').html(data.cart_view);
                        // Re-initialize plus/minus controls
                        setTimeout(function() {
                            AIZ.extra.plusMinus();
                        }, 100);
                    }
                } else if (data.status === 0) {
                    // Error - revert quantity and show message
                    if (data.message) {
                        AIZ.plugins.notify('warning', data.message);
                    }
                    quantityInput.val(currentQuantity);
                    if (data.cart_view) {
                        $('#cart-details').html(data.cart_view);
                    }
                }
            }).fail(function(xhr) {
                $('.aiz-refresh').removeClass('active');
                AIZ.plugins.notify('danger', "{{ translate('An error occurred while updating bundle quantity.') }}");
                quantityInput.val(currentQuantity);
                
                // Try to get error message from response
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    AIZ.plugins.notify('warning', xhr.responseJSON.message);
                }
            });
        }
        
        // Remove entire bundle from cart
        // Accepts either composite key (group_product_id_hash) or just group_product_id
        function removeGroupProductFromCart(groupKey) {
            if (!confirm("{{ translate('Are you sure you want to remove this bundle from cart?') }}")) {
                return;
            }
            
            $('.aiz-refresh').addClass('active');
            
            $.post('{{ route('cart.removeGroupProductFromCart') }}', {
                _token: AIZ.data.csrf,
                group_key: groupKey
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-details').html(data.cart_view);
                AIZ.extra.plusMinus();
                AIZ.plugins.notify('success', "{{ translate('Bundle removed from cart') }}");
                $('.aiz-refresh').removeClass('active');
            }).fail(function() {
                AIZ.plugins.notify('danger', "{{ translate('An error occurred while removing bundle.') }}");
                $('.aiz-refresh').removeClass('active');
            });
        }


        // coupon apply
        $(document).on("click", "#coupon-apply", function() {
            @if (Auth::check())
                @if(Auth::user()->user_type != 'customer')
                    AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to apply coupon code.') }}");
                    return false;
                @endif

                var data = new FormData($('#apply-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.apply_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                        $("#cart_summary").html(data.html);
                    }
                });
            @else
                $('#login_modal').modal('show');
            @endif
        });

        // coupon remove
        $(document).on("click", "#coupon-remove", function() {
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                var data = new FormData($('#remove-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.remove_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        $("#cart_summary").html(data);
                    }
                });
            @endif
        });

    </script>
@endsection
