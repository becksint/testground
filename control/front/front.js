//document.getElementByTagName("button").addEventListener("click", myFunction, true);
//scan for ids/classes --> trigger functions :: add as a set of api
//all items to have data-action, etc
//console.log('kljlkj');
function showOffer(id, offering) {
    const offer = document.querySelector('#' + id);
    if (offer.length == 0) {
        //document.getElementById("#products").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById(id).innerHTML = this.responseText;
                xslide();
                //cartListen(offering + ' article'); //adds cart button
                //onLoadCartNumbers(); //initialises cart -- you can turn these into php functions where possible

            }
        };
        const collect = offer.dataset.item + '__' + offer.dataset.display  + '__' + offer.dataset.specific  + '__' + offer.dataset.style  + '__' + offer.dataset.filters  + '__' + offer.dataset.paginate + '__' + id ;
        //xmlhttp.open("GET", "https://lecbiotec.co.za/control/front/front.php?want=" + collect, true);
        xmlhttp.open("GET", "control/front/front.php?want=" + collect, true);
        xmlhttp.send();        
    }    
}
var products =[];
function cartListen(cartclass){
    let carts = document.querySelectorAll('.'+cartclass);
    if(typeof carts != "undefined"){ carts.forEach(dataPush); }
    
}
function dataPush(item, index, arr) {
      products.push({ "id":item.dataset.id,  "name":item.dataset.name, "variants":item.dataset.sizes, "price":item.dataset.prices,  "thumb":item.dataset.thumb,"note":item.dataset.note, "inCart":0 , "inTotal":0  });
      //console.log(item);
      let cartbutton = document.querySelectorAll('.addtoquote');
      cartbutton[index].addEventListener('click', function(){
            cartNumbers(products[index]);
       } )
      //console.log(index);
}
function localRead(){
    let cartCount       = localStorage.getItem('cartCount');  //get full cart count from local storage
    let cartCost        = localStorage.getItem('cartCost');           //get full cart cost from local storage
    let cartProducts    = localStorage.getItem('cartProducts');     //get all product arrays from local storage
    cartCount = parseInt(cartCount);      //convert to an interger
    cartCost = parseInt(cartCost);                  //convert to an interger--float        
    cartProducts = JSON.parse(cartProducts);              //convert to js array from json
    let dataread = { totalCount: cartCount, totalCost:cartCost, products : cartProducts};
    return dataread;
}
function localModify(cartCount, cartCost, cartProduct,x){ //modifies a single product + global
            let newinCart = cartProduct.inCart+1-1+x;
            let newPrice = (1+x-1)*cartProduct.price;      //set new product cart total cost
            cartCount = cartCount + x; //cart count
            cartCost = cartCost + newPrice;  //cart cost
            cartProduct.inCart = newinCart; //update incart count
            cartProduct.inTotal = cartProduct.inTotal + newPrice; //update intotal price
            let datawrite = { totalCount: cartCount, totalCost:cartCost, product : cartProduct};
            return datawrite;
}
function localWrite(totalCount, totalCost, products){     
            localStorage.setItem('cartCost', totalCost);
            localStorage.setItem('cartCount', totalCount);
            localStorage.setItem('cartProducts', JSON.stringify(products));
            document.querySelector('.cart span').textContent = totalCount; 
}
function cartNumbers(product){
    let readLocal = localRead(); 
    let productprice = parseInt(product.price); //make float    
    if(readLocal.products == null  ){
           readLocal.products =  {[product.id]:product} ;
        }
    if(readLocal.totalCount){
        totalCount = readLocal.totalCount;
        totalCost = readLocal.totalCost;           
    }    
    else{     //initialising
        totalCount = 0; 
        totalCost = 0;         
    }
     let dataModified =  localModify(totalCount, totalCost, product,1);
     readLocal.products[product.id] = product;
    localWrite(  dataModified.totalCount, dataModified.totalCost ,readLocal.products);
}
function removeCart(n){
    let readLocal = localRead(); 
    let x = 0-readLocal.products[n].inCart;
    let dataModified =  localModify(readLocal.totalCount, readLocal.totalCost, readLocal.products[n],x);
    delete readLocal.products[n];
    localWrite(  dataModified.totalCount, dataModified.totalCost ,readLocal.products);
    var element = document.getElementById('cartitem'+n);
    element.parentNode.removeChild(element);
}
function modifyCart(n, x){
    let readLocal = localRead(); 
    let newinCart = readLocal.products[n].inCart+1-1+x;   //set new product cart total count    
    if(newinCart > -1){ //only modify if 0 and above
            let dataModified =  localModify(readLocal.totalCount, readLocal.totalCost, readLocal.products[n],x);
            readLocal.products[n] = dataModified.product; //update product in cart
            localWrite(  dataModified.totalCount, dataModified.totalCost ,readLocal.products);
      }
}
function onLoadCartNumbers(){
    let cartCount = localStorage.getItem('cartCount'); 
    cartCount = parseInt(cartCount); //make into integer from string
    if( cartCount ){
        document.querySelector('.cart span').textContent = cartCount ;
    }
}
function reduceN(par){
    var x = par.parentElement.getElementsByTagName('input')[0].getAttribute('value');
    n = par.parentElement.dataset.id;
    modifyCart(n, -1);
    x--;
    if(x<0){ x = 0; }
    par.parentElement.getElementsByTagName('input')[0].setAttribute("value",  x);
    //modify local storage
    //console.log(x)
}
function increaseN(par){
    var x = par.parentElement.getElementsByTagName('input')[0].getAttribute('value');
    n = par.parentElement.dataset.id;
    modifyCart(n, 1);
    x++;
    //if(x<0){ x = 0; } --place max
    par.parentElement.getElementsByTagName('input')[0].setAttribute("value",  x);
    //modify local storage
}
function displayCart(){
    let cartItems = localStorage.getItem("cartProducts");
    cartItems = JSON.parse(cartItems);
    let productcontainer = document.querySelector('.cartform');
    //console.log(productcontainer);
    if(cartItems && productcontainer){
        productcontainer.innerHTML = '';
        Object.values(cartItems).map(item =>{
            var str = item.variants;
            var res = str.split("___");
            var spec = '';
            var qty = item.inCart;
            var qty2 = 0;
            res.forEach(function(value, key) {
                      spec += '<div data-id="'+ item.id + '" ><span>' + value + '</span><button onclick="reduceN(this)">-</button><input type="number" name=product['+ item.id + '][qty]['+ value + '] value="'; 
                      if (key !== 0) {  spec +=  qty2 + '">'; }
                      else {  spec +=   qty + '">' ; }
                      return spec += '<button onclick="increaseN(this)">+</button></div>'; 
            });
            productcontainer.innerHTML += '<article id="cartitem'+ item.id + '"  class="cartitem"><figure class="'+ item.thumb + '"><button onclick="removeCart('+ item.id + ')" >x</button></figure><header><h2>'+ item.name + '</h2><p>'+ item.note + '</p></header><main>' + spec + ' </main><footer>R ' + item.inTotal + '</footer></article>';
        });
    }
}

/*HIDE ON CLICK*/
function hideme(iddiv) {
  var x = document.getElementById(iddiv);
  console.log(x);
  if (x.style.display === "none") {  x.style.display = "block"; } 
  else { x.style.display = "none"; }
}

function logSubmit(event) {
 // form.textContent = 'processing...';
    var data = new FormData(form);
    //data.append('input', 'dddd');
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            form.innerHTML = this.responseText;
        }
    };
    xmlhttp.open("POST", "control/front/front.php", true);
    xmlhttp.send(data);  
    event.preventDefault();
}


//xslidemotion(0, 0, 3000); //start slideshow
//xslidemotion(1, 0, 2000);
function xslide(){
    var clicker =  document.querySelectorAll(".slideframe .xslidenav button");
    var lister = document.getElementsByClassName("slideframe");
    if (typeof clicker != "undefined" && typeof lister != "undefined"){ 
        for (var i = clicker.length - 1; i >= 0; i--) { clicker[i].setAttribute("onclick", 'showDivs(this)'); }
        for (var i = lister.length - 1; i >= 0; i--) { showDivs(lister[i]);  }
    }
}

function showDivs(elemt) {
  var n = elemt.getAttribute("data-goto");
  var id = elemt.getAttribute("data-id");
  var i;
  var x = document.querySelectorAll("#"+ id + ">.xslide>div");        //frames as array
  var dots = document.querySelectorAll("#"+ id + ">.xslidenav li");   //navigation as array
  var prev = document.querySelectorAll("#"+ id + ">.xslidenav>button")[0]; //prev button
  var next = document.querySelectorAll("#"+ id + ">.xslidenav>button")[1]; //next button
  var prevN=n-1;
  var nextN=n-1+2;
  if(prevN < 1){ prevN = x.length; }
  if(nextN > x.length) {  nextN = 1;  }

  for (i = 0; i < x.length; i++) {   x[i].style.display = "none";     }
  for (i = 0; i < dots.length; i++) {    dots[i].className = dots[i].className.replace(" xslidenavselected", "");  }
   x[n-1].style.display =  "grid";  
  dots[n-1].className += " xslidenavselected";
  prev.setAttribute("data-goto", prevN); 
  next.setAttribute("data-goto", nextN); 
}

function xslidemotion(slidepick, myIndex, timegap) {
  var i;
  var x = document.getElementsByClassName("xslide")[slidepick].querySelectorAll('div');
  var dots = document.getElementsByClassName("xslidenav")[slidepick].querySelectorAll('li');
  //console.log(x);
  for (i = 0; i < x.length; i++) {   x[i].style.display = "none";   }
  for (i = 0; i < dots.length; i++) {    dots[i].className = dots[i].className.replace(" xslidenavselected", "");  }

  myIndex++;
  if (myIndex > x.length) {myIndex = 1}    
  x[myIndex-1].style.display = "block";  
  dots[myIndex-1].className += " xslidenavselected";
  setTimeout(function(){xslidemotion(slidepick, myIndex, timegap);}, timegap);    
}

const form = document.getElementById('contactform');
if(form){
form.addEventListener('submit', logSubmit);
}


