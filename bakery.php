<?php
    include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bakery.css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <title>Document</title>
</head>
<body ng-app="bakery"  ng-controller="pictures" style="background-color: red;">
    <?php 
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $con=connect_db();
            try{
                if($con->connect_error){
                    throw new Exception("Connect failed");
                }
                $name=$_POST["fname"]."_".$_POST["lname"];
                $address=$_POST["add1"]." ".$_POST["add2"].", ".$_POST["city"].", ".$_POST["state"];
                $insert="INSERT INTO bakery_tb (name,email,phone,address,postal_code,price) VALUES ('".$name."','".$_POST["email"]."','".$_POST["phone"]."','".$address."','".$_POST["postal"]."','".$_POST["total"]."')";
                $con->query($insert);

                $select="SELECT order_id FROM bakery_tb WHERE email='".$_POST["email"]."'";
                $result=$con->query($select);
                echo $_POST["email"];
                if($result->num_rows>0){
                    while($row=$result->fetch_assoc()){
                        $id=$row["order_id"];
                    }
                    echo $id;
                }

                $br="";
                $cc="";
                $sp="";
                $cca="";
                $dn="";
                $sc="";
                if(isset($_POST["br"])){
                    $br=$_POST["br"];
                }
                if(isset($_POST["cc"])){
                    $cc=$_POST["cc"];
                }
                if(isset($_POST["sp"])){
                    $sp=$_POST["sp"];
                }
                if(isset($_POST["cca"])){
                    $cca=$_POST["cca"];
                }
                if(isset($_POST["dn"])){
                    $dn=$_POST["dn"];
                }
                if(isset($_POST["sc"])){
                    $sc=$_POST["sc"];
                }

                $insert="INSERT INTO bakery_products_tb (orderid,bread,	choco_cookie,sourcerry,cupcake,donuts,strawberry) VALUES ('".$id."','".$br."','".$cc."','".$sp."','".$cca."','".$dn."','".$sc."')";
                $con->query($insert);
                
             


            }catch(Exception $ex){
                echo $ex->getMessage();
            }
        }
    ?>

    <div class="backGround">
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
            <div id="heading">
                <h2>Bakery Order Form</h2>
            </div>
            <div class="main">
                <div>
                    <p><span>1.</span>Choose what to order</p>
                    <p  class="inf" style="display:flex;justify-content:space-between;">
                        <span>Listing  products</span><span ng-click="showShoplists()"><i class="fas fa-shopping-cart"></i></span>
                    </p>
                    <div class="items">
                        <div ng-repeat="x in products" ng-click="showPopups($index)">
                           <img src="{{x.image}}" alt="{{x.name}}" value={{x.key}}>
                           <p value={{x.key}}>{{x.name}}</p>
                           <p value={{x.key}}>Price: <span style="color: orange;" ng-bind="x.price | currency"></span></p>      
                        </div>
                    </div>
                    <div class="pricing">
                        <div>
                            <span>Amount:</span>
                            <span>{{totalAmount}}</span>
                        </div>
                        <div>
                            <span>Subtotal:</span>
                            <span>{{subtotal | currency}}</span>
                        </div>
                        <hr>
                        <p>Total: <span><input style="background-color:transparent;border:none;" name="total" type="text" value="{{subtotal*1.10 | currency}}"></span></p>
                    </div>
                </div>
                <div id="sub" >
                    <p><span>2.</span>Full name</p>
                    <input name="fname" ng-model="fname" ng-required="true" class="inf" type="text" placeholder="First Name">
                    <input name="lname" ng-model="lname" ng-required="true"  class="inf" type="text" placeholder="Last Name">
                </div>
                <div style="position: relative;">
                    <p><span>3.</span>Email</p>
                    <i class="fas fa-envelope" style="position: absolute;z-index: 22;font-size: 20px;margin-left: 10px;top: 35px;"></i>
                    <input name="email" ng-model="email" ng-required="true" class="inf" type="email">
                </div>
                <div style="position: relative;">
                    <p><span>4.</span>Phone number</p>
                    <i class="fas fa-phone-square"  style="position: absolute;z-index: 22;font-size: 20px;margin-left: 10px;top: 35px;"></i>
                    <input name="phone" ng-model="phone" ng-required="true" class="inf" type="number">
                </div>
                <div style="position: relative;">
                    <p><span>5.</span>What is your address</p>
                    <i class="fas fa-map-marker-alt"  style="position: absolute;z-index: 22;font-size: 20px;margin-left: 10px;top: 35px;"></i>
                    <input name="add1" ng-model="address" ng-required="true" class="inf" type="text" placeholder="     Adress">
                    <input name="add2" ng-model="address2" ng-required="true" class="inf" type="text"  placeholder="Address Line 2">
                    <input name="city" ng-model="city" ng-required="true" class="inf" type="text"  placeholder="City">
                    <input name="state" ng-model="state" ng-required="true" class="inf" type="text"  placeholder="State Address">
                    <input name="postal" ng-model="postal" ng-required="true" class="inf" type="text"  placeholder="Postal Code">
                </div>
                <div id="submit">
                    <input type="submit" value="Submit">
                </div>
            </div>

            <div id="popupBgc" ng-show="popbg">
                <div class="popupForm"  ng-repeat="x in products"  ng-show="{{x.key}}">
                    <div>
                        <img src="{{x.image}}">
                    </div>
                    <div>
                        <h2>{{x.name}}</h2>
                        <div class="popupPrice">
                            <span>Price</span>
                            <span>{{x.price | currency}}</span>
                        </div>
                        <div class="popupBottons">
                            <input type="number" class="popnums" ng-click="resetvalue($event)" ng-blur="addtoBascket($event,$index)" ng-init="popItemPrice=0" ng-model="popItemPrice">
                            <button ng-click="showPopups($index)">Add To Basket</button>
                        </div>                
                    </div>
                </div>
            </div>

            <div id="listBgc" ng-show="shoppingListShow">
                <div class="listForm" style="position: relative;">
                    <i class="fas fa-times" style="position: absolute;right: 50px; cursor: pointer; z-index:99;" ng-click="showShoplists()"></i>
                    <table style="position: relative;">
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                            <th>Total</th>
                        </tr>
                        <tr ng-repeat="x in products" ng-show="{{x.quantity}}">
                            <td name="{{x.name}}">{{x.name}}</td>
                            <td class="listbgcprice"  value="{{x.price}}">{{x.price}}$</td>
                            <td><input name="{{x.key}}"  ng-click="resetvalue($event)" ng-blur="shoppinglistculculate($event,$index)"  class="listbgctotalnum" type="number" value="0"></td>
                            <td  type="text">
                                <input type="text" class="listbgctotal" value="0" style="padding: 0;width:20px; background-color:transparent;border: none;outline: none;font-size: 16px;">$
                            </td>
                        </tr>
                    </table>
                    <div>
                        <span>Subtotal</span>
                        <span id="listbgcSubtotal">{{subtotal | currency}}</span>
                    </div>
                    <div>
                        <span ng-click="shoppinglistculculate()" >Total</span>
                        <span>{{subtotal*1.10 | currency}}</span>
                    </div>
                    <div class="listButtons">
                        <button ng-click="showShoplists()">Continue Shopping</button>
                        <button ng-click="showShoplists()"><a href="#sub" style="text-decoration: none; color:black;">Finish Shopping</a></button>
                    </div>
                </div>
            </div>

        </form>
    </div>
    
    
    <script>
        var bakeries=angular.module("bakery",[]);
        bakeries.controller
        bakeries.controller("pictures",function($scope,$attrs){
            $scope.products=[
                {image:"image/bread.png",name:"Bread",price:"2.00",quantity:"cbr",key:"br",conne:"key1"},
                {image:"image/chocCookie.png",name:"Chocolate Cookie",price:"1.00",quantity:"ccc",key:"cc",conne:"key2"},
                {image:"image/sourCherry.png",name:"SourCherry Pie",price:"5.00",quantity:"csp",key:"sp",conne:"key3"},
                {image:"image/cupcake.png",name:"Cupcake",price:"2.50",quantity:"ccca",key:"cca",conne:"key4"},
                {image:"image/donuts.png",name:"Donuts",price:"3.00",quantity:"cdn",key:"dn",conne:"key5"},
                {image:"image/strawChoc.png",name:"Strawberry Chocolate",price:"5.50",quantity:"csc",key:"sc",conne:"key6"},
            ];
            //modelbox show hide
            $scope.showPopups=function(i){
                $scope.popbg=!$scope.popbg;
                if(i==0){
                   $scope.br=!$scope.br; 
                }
                else if(i==1){
                    $scope.cc=!$scope.cc; 
                }
                else if(i==2){
                    $scope.sp=!$scope.sp; 
                }
                else if(i==3){
                    $scope.cca=!$scope.cca; 
                }
                else if(i==4){
                    $scope.dn=!$scope.d; 
                }
                else if(i==5){
                    $scope.sc=!$scope.sc; 
                }
            }            
            //ShoppingList  show hide
            $scope.showShoplists=function(){
                 $scope.shoppingListShow=!$scope.shoppingListShow;
            }
            // Add products to Shoppinglist and culculate  from popup
            $scope.addtoBascket=function(e,i){
                if(i==0){
                    $scope.cbr=true; 
                }
                else if(i==1){
                    $scope.ccc=true; 
                }
                else if(i==2){
                    $scope.csp=true; 
                }
                else if(i==3){
                    $scope.ccca=true; 
                }
                else if(i==4){
                    $scope.cdn=true; 
                }
                else if(i==5){
                    $scope.csc=true; 
                }
                var elements=document.getElementsByClassName("listbgctotalnum");
                var contain=document.getElementsByClassName("listbgctotal");
                var price=document.getElementsByClassName("listbgcprice");
                var subtotal=document.getElementById("listbgcSubtotal");
                elements[i].value=e.target.value;
                contain[i].value=price[i].getAttribute("value")*elements[i].value;
                var sub=0;
                var count=0;
                for(var j=0;j<elements.length;j++){
                    count=count+parseInt(elements[j].value);
                    sub = sub + parseFloat(contain[j].value);
                }
                $scope.subtotal=sub;
                $scope.totalAmount=count;
            }
            //shoppinglist culculation
            $scope.shoppinglistculculate=function(e,i){
                var contain=document.getElementsByClassName("listbgctotal");
                var subtotal=document.getElementById("listbgcSubtotal");
                var elements=document.getElementsByClassName("listbgctotalnum");
                e.target.parentElement.nextElementSibling.children[0].value=e.target.value*e.target.parentElement.previousElementSibling.getAttribute("value");
                var sub=0;
                var count=0;
                for(var j=0;j<contain.length;j++){
                    count=count+parseInt(elements[j].value);
                    sub = sub + parseFloat(contain[j].value);
                }
                $scope.subtotal=sub;
                $scope.totalAmount=count;
                document.getElementsByClassName("popnums")[i].value=e.target.value;
           }
            //reset value inside input
            $scope.resetvalue=function(e){
                e.target.value="";
            }
            // hide popup and shopping list
            // document.addEventListener("click",function(e){
            //     console.log(e.target);
            //     var popbghide=document.getElementById("popupBgc");
            //     var shoppingbg=document.getElementById("listBgc");
            //     if(e.target==popbghide){
            //         $scope.popbg=false;
            //         $scope.br=false; 
            //         $scope.cc=false; 
            //         $scope.sp=false;
            //         $scope.cca=false;
            //         $scope.dn=false; 
            //         $scope.sc=false; 
            //         alert("a");
            //     }
            //     else if(e.target==shoppingbg){
            //         $scope.shoppingListShow=false;
            //     }
            // });
        });
    </script>

</body>
</html>