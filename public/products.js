var changePage = function(page) {
    $('.page').hide();
    if (page.length) {
        $('.' + page + 'Page').show();
    }
    console.log(page);
    if (page == 'index' || page == 'cart') {
        $('.indexPage .productsTable').html('Loading');
        $.ajax({
            url: '/'+page,
            dataType: 'JSON',
            success: function(response){
                getContent(response,page);
            }
        });
    }
}

var getPage = function() {
    var hash = window.location.hash;
    if (hash.indexOf('#') == 0) {
        hash = hash.substr(1);
    }
    if (hash.indexOf('/') == 0) {
        hash = hash.substr(1);
    }
    return hash.split('/').shift();
}

$(document).ready(function() {
    var page = getPage();
    changePage(page ? page : 'index');
});

$(window).bind('hashchange', function() {
    changePage(getPage());
});

function addToCart(id){
    $.ajax({
        url: '/add-to-cart/'+id,
        method: 'GET',
        success: function(){
            changePage('index');
        }
    });
};

function removeFromCart(id){
    $.ajax({
        url: '/remove-from-cart/'+id,
        method: 'GET',
        success: function(){
            changePage('cart');
        }
    });
};

function checkout(){
    var email = document.getElementById('email');
    if(email.value == ""){
        alert("Email required");
    }
    else{
        $.ajax({
            url: '/checkout/'+email.value,
            method: 'GET',
            success: function(){
                changePage('cart');
            }
        });
    }
};

function updateQuantity(id){
    var quantity = document.getElementById('quantity'+id);
    if(quantity.value == ""){
        alert('Quantity required');
    }
    else{
        $.ajax({
           url: '/update-quantity/'+quantity.value+'/'+id,
           method: 'GET',
           success: function(){
               changePage('cart');
           }
        });
    }

}

function getContent(response,page) {
    console.log(response);
    var html = [
        '<table>',
        '<tr>',
        '<th><h1>Products</h1></th>',
        '</tr>',
    ];

    if(page == 'index'){
        var products = response;
    }
    else if(page == 'cart'){
        var products = response.products;
    }
    $.each(products, function(index, value) {
        html.push('<tr>');
        html.push('<td>');
        html.push('<p>'+value.title+'</p>');
        html.push('</td>');
        html.push('<td>');
        html.push('<p>'+value.description+'</p>');
        html.push('</td>');
        html.push('<td>');
        if(page == 'cart'){
            html.push('<p>Quantity: '+response.cart[value.ID]+'</p>')
        }
        html.push('<p>'+value.price  +'$</p>');
        html.push('</td>');
        html.push('<td>');
        html.push('<img src="'+value.image+'" style="height:15%">');
        html.push('</td>');
        html.push('<td>');

        if(page == 'index'){
            html.push('<button class="addToCart" id="'+value.ID+'" onclick=addToCart(this.id)>Add to cart</button>');
        }
        else if(page == 'cart'){
            html.push('<input type="number" class="form" id="quantity'+value.ID+'" min="0" max="100" value="'+response.cart[value.ID]+'" placeholder="Quantity" required>');
            html.push('<button class="updateQuantity" id="'+value.ID+'" onclick=updateQuantity(this.id)>Save</button>');
            html.push('<button class="removeFromCart" id="'+value.ID+'" onclick=removeFromCart(this.id)>Remove from cart</button>');
        }


        html.push('</td>');
        html.push('</tr>');
    });

    html.push('</table>');
    if(page == 'index'){
        $('.indexPage .productsTable').html(html);
    }
    else{
        if(response.cart != null){
            html.push('<tr>');
            html.push('<td>');
            html.push('<h3>Total:'+ response.total +'$</h3>');
            html.push('</td>');
            html.push('</tr>');
            html.push('<tr>');
            html.push('<td>');
            html.push('<input type="text" class="form" id="email" maxlength="30" placeholder="Enter email" required>');
            html.push('<button class="checkout" onclick=checkout()>Checkout</button>');
            html.push('</td>');
            html.push('</tr>');
        }
        $('.cartPage .productsTable').html(html);
    }
}
