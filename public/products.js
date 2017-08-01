var logged = false;
var changePage = function(page) {
    $('.page').hide();
    if (page.length) {
        $('.' + page + 'Page').show();
    }
    if (page == 'index' || page == 'cart' || page == 'admin') {
        $('.indexPage .productsTable').html('Loading');
        $.ajax({
            url: '/'+page,
            dataType: 'JSON',
            success: function(response){
                if(page == 'admin' && response.error){
                    alert("Access denied");
                    location.hash = 'login';
                }
                else{
                    getContent(response,page);
                }
            }
        });
    }
    else if(page == 'add' && !logged){
        alert("Access denied");
        location.hash = 'login';
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
            location.hash = 'index';
            changePage('index');
        }
    });
};

function removeFromCart(id){
    $.ajax({
        url: '/remove-from-cart/'+id,
        method: 'GET',
        success: function(){
            location.hash = 'cart';
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
                location.hash = 'cart';
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
               location.hash = 'cart';
           }
        });
    }

}

function add(){
    document.getElementById('addProduct').setAttribute('onclick',"addProducts(0,'create')");
    location.hash = 'add';
}

function addProducts(id ,status){
    $('#myForm').attr('action', '/stored/'+id+'/'+status);
    $('#myForm').submit();
    $('#formMessage').html('Product added');
    location.hash = 'admin';
}
function deleteProduct(id){
    $.ajax({
        url : '/delete/'+id,
        method : "GET",
        success : function(){
            location.hash = 'admin';
            changePage('admin');
        }
    })
}
function updateProduct(id){
    location.hash = 'add';
    $.ajax({
        url : '/update/'+id,
        method : 'GET',
        success : function(response){
            document.getElementById('title').value = response.title;
            document.getElementById('description').value = response.description;
            document.getElementById('price').value = response.price;
            document.getElementById('addProduct').setAttribute('onclick','addProducts('+id+',"update")');
        }
    });
}

function login(){
    var email = document.getElementById('user');
    var password = document.getElementById('pass');
    if(email.value == '' || password.value == ''){
        alert("Please enter both username and password.")
    }
    else{
        var data = {
            user : email.value,
            pass : password.value
        };

        $.ajax({
           url : '/login-attempt',
            method : "POST",
            data : data,
            success : function(response){
               if(response.success == 'true'){
                    alert("Login Successful");
                    location.hash = 'admin';
                    logged = true;
               }
               else{
                   alert('Login Failed');
               }
           }
        });
    }
}

function logout(){
    $.ajax({
        url : '/logout',
        method : 'GET',
        success : function(){
            location.hash = 'index';
            logged = false;
        }
    });
}

function getContent(response,page) {
    var html = [
        '<table>',
        '<tr>',
        '<th><h1>Products</h1></th>',
        '</tr>',
    ];

    if(page == 'index' || page == 'admin'){
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
            html.push('<button class="addToCart btn btn-warning" id="'+value.ID+'" onclick=addToCart(this.id)>Add to cart</button>');
        }
        else if(page == 'cart'){
            html.push('<input type="number" class="form" id="quantity'+value.ID+'" min="0" max="100" value="'+response.cart[value.ID]+'" placeholder="Quantity" required>');
            html.push('<button class="updateQuantity btn btn-info" id="'+value.ID+'" onclick=updateQuantity(this.id)>Save</button>');
            html.push('<button class="removeFromCart btn btn-warning" id="'+value.ID+'" onclick=removeFromCart(this.id)>Remove from cart</button>');
        }
        else if(page == 'admin'){
            html.push('<button class="update btn btn-warning" id="'+value.ID+'" onclick=updateProduct(this.id)>Edit</button>');
            html.push('<button class="delete btn btn-danger" id="'+value.ID+'" onclick=deleteProduct(this.id)>Delete</button>');
        }


        html.push('</td>');
        html.push('</tr>');
    });

    html.push('</table>');
    if(page == 'index'){
        $('.indexPage .productsTable').html(html);
    }
    else if(page == 'cart'){
        if(response.cart != null){
            html.push('<tr>');
            html.push('<td>');
            html.push('<h3>Total:'+ response.total +'$</h3>');
            html.push('</td>');
            html.push('</tr>');
            html.push('<tr>');
            html.push('<td>');
            html.push('<input type="text" class="form" id="email" maxlength="30" placeholder="Enter email" required>');
            html.push('<button class="checkout btn btn-info" onclick=checkout()>Checkout</button>');
            html.push('</td>');
            html.push('</tr>');
        }
        $('.cartPage .productsTable').html(html);
    }
    else if(page == 'admin'){
        html.push('<tr><td><button class="btn btn-info" id="add" onclick="add()">Add product</button></td></tr>');
        html.push('<tr><td><button class="btn btn-info" onclick=logout()>Logout</button></td></tr>');
        $('.adminPage .productsTable').html(html);
    }
}
