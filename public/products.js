var logged = false;
var changePage = function(page) {
    $('.page').hide();
    if (page.length) {
        $('.' + page + 'Page').show();
    }
    console.log(page);
    if (page == 'index' || page == 'cart' || page == 'admin') {
        $('.indexPage .productsTable').html('Loading');
        $.ajax({
            url: '/'+page,
            dataType: 'JSON',
            success: function(response){
                getContent(response,page);
            }
        });
    }
    else if(page == 'login'){
        setLoginPage();
    }
    else if(page == 'add'){
        setAddProductPage();
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

function addProduct(id ,status){
    console.log("add product");
    var data = {
        title : document.getElementById('title').value,
        description : document.getElementById('description').value,
        price : document.getElementById('price').value,
        image : document.getElementById('image').value
    }
    $.ajax({
        url : '/stored/'+id+'/'+status,
        method : 'POST',
        data: data,
        success : function(){
            changePage('admin');
        }
    })
}
function deleteProduct(id){
    console.log("delete product");
    $.ajax({
        url : '/delete/'+id,
        method : "GET",
        success : function(){
            changePage('admin');
        }
    })
}
function updateProduct(id){
    console.log("update product");
    location.hash = 'add';
    $.ajax({
        url : '/update/'+id,
        method : 'GET',
        success : function(response){
            document.getElementById('title').value = response.title;
            document.getElementById('description').value = response.description;
            document.getElementById('price').value = response.price;
            document.getElementById('addProduct').setAttribute('onclick','addProduct('+id+',"update")');
        }
    });
}

function login(){
    var user = document.getElementById('user');
    var pass = document.getElementById('pass');
    if(user.value == '' || pass.value == ''){
        alert("Please enter both username and password.")
    }
    else if(user.value != 'admin' || pass.value != 'admin'){
        alert("Login failed!")
    }
    else{
       logged = true;
       location.hash = 'admin';
    }
}

function logout(){
    logged = false;
    location.hash = 'index';
}
function setLoginPage(){
    var html = [
        '<table>',
        '<tr>',
        '<th><h1>Login</h1></th>',
        '</tr>',
        '<tr>',
        '<td>',
        '<input type="text", class="form" id="user" placeholder="Username" required>',
        '</td>',
        '</tr>',
        '<tr>',
        '<td>',
        '<input type="password", class="form" id="pass" placeholder="Password" required>',
        '</td>',
        '</tr>',
        '<tr>',
        '<td>',
        '<button class="btn btn-warning" id="login" onclick=login()>Login</button>',
        '</td>',
        '</tr>',
        '</table>'
    ]
    $('.loginPage .form').html(html);
}
function setAddProductPage(){
    var html = [
        '<table>',
        '<tr>',
        '<th><h1>Add product</h1></th>',
        '</tr>',
        '<form class="form-group" action="" enctype="multipart/form-data">',
        '<tr>',
        '<td>',
        '<input type="text", class="form-control" id="title" placeholder="Title" required>',
        '</td>',
        '</tr>',
        '<tr>',
        '<td>',
        '<input type="textarea", class="form-control" id="description" placeholder="Description" required>',
        '</td>',
        '</tr>',
        '<tr>',
        '<td>',
        '<input type="number", class="form-control" id="price" placeholder="Price" required>',
        '</td>',
        '</tr>',
        '<tr>',
        '<td>',
        '<h4>Image </h4>',
        '<input type="file", class="form" id="image" required>',
        '</td>',
        '</tr>',
        '<tr>',
        '<td>',
        '<button class="btn btn-warning" id="addProduct" onclick=addProduct(0,"create")>Add</button>',
        '</td>',
        '</tr>',
        '</form>',
        '</table>'
    ]
    $('.addPage .form').html(html);
}
function getContent(response,page) {
    console.log(response);
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
        html.push('<tr><td><a href="#add" class="btn btn-info">Add product</a></td></tr>');
        html.push('<tr><td><button class="btn btn-info" onclick=logout()>Logout</button></td></tr>');
        $('.adminPage .productsTable').html(html);
    }
}
