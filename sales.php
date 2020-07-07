<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
	<title>BugTrakt Pricing</title>
	<meta name="author" content="William West">
	<meta name="description" content="A simple web bug tracker">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="salesStyle.css">
	<link rel="icon" type="image/x-icon" href=""/>
</head>

<body>
<?php 
    //calling our header
    include_once("head.php");
    ?>
    
	<main>
        <!--Starting with a big bold beautiful heading.-->
        <div id="salesOne">
            <h1 id="salesOne">The right pricing for all of your needs.</h1>
            <p>Whether you're a small organization or a multi trillion dollar monopoly, it's clear, you need us and we can charge you whatever we want.  So here we'll provide you with our clearly patently insane prices for something we took from the internet and started hosting, because we owned the domain name for it and will trick people who don't know into paying for it by pretending we have exclusive hosting rights to our product.</p>
            <div id="pricingContainer">
            <!-- This outer container is the outer most box for our pricing sheet -->
            <div id="pricingOne">
                <!-- Then we have an inner contained element so we can reliably have whitespace, calculated easily. -->
                <div id="pricingTwo">
                    <h1>The Small Cat.</h1>
                    <div id="minorPadding">
                    <p>We get it, you're not the biggest company (yet!), but you have a lot of money from angel investors and cant be bothered to code such simple things.  Really, we do understand, and your investors couldn't be happier with your decision to pay us their money they expect dividends on in the super near future, not just the near future.</p>
                        </div>
                    <ul id="pricingList">
                        <li>Item one</li>
                        <li id="altList">Item two</li>
                        <li>Item three</li>
                        <li id="altList">Item four</li>
                        <!--empty items for the other lists! -->
                        <li>&lrm;</li>
                        <li id="altList">&lrm;</li>
                        <li>&lrm;</li>
                        <li id="altList">&lrm;</li>
                        <li>&lrm;</li>
                        <li id="altList">&lrm;</li>
                        
                    </ul>
                    <div #id="price"><h2 id="price">$100/per user/per month.<span id="caution">*</span> </h2></div>

                </div>
            </div>
            <div id="pricingOne">
                <div id="pricingTwo">
                    <h1>The Big Cat.</h1>
                    <div id="minorPadding">
                    <p>Dont worry about Joe Exotic, big cat!  He's in jail now and you're very safe!</p>
                        </div>
                    <ul id="pricingList">
                        <li>Item one</li>
                        <li id="altList">Item two</li>
                        <li>Item three</li>
                        <li id="altList">Item four</li>
                        <li>item five</li>
                        <li id="altList">item six</li>
                        <li>&lrm;</li>
                        <li id="altList">&lrm;</li>
                        <li>&lrm;</li>
                        <li id="altList">&lrm;</li>
                        
                    </ul>
                    <div #id="price"><h2 id="price">$1,000/per user/per month.<span id="caution">*</span> </h2></div>
                </div>
            </div>
            <div id="pricingOne">
                <div id="pricingTwo">
                    <h1>Miss Monopoly.</h1>
                    <div id="minorPadding">
                    <p>Look's like you've put Uncle Pennybags to shame, Miz Monopoly!  Did I mention how young and pretty you still are?  Aww no, 200 years old is perfectly natural to have never married anyone!</p>
                        </div>
                    <ul id="pricingList">
                        <li>Item one</li>
                        <li id="altList">Item two</li>
                        <li>Item three</li>
                        <li id="altList">Item four</li>
                        <li>item five</li>
                        <li id="altList">item six</li>
                        <li>item seven</li>
                        <li id="altList">item eight</li>
                        <li>item nine</li>
                        <li id="altList">item ten</li>
                        
                    </ul>
                    <div #id="price"><h2 id="price">$10,000/per user/per month.<span id="caution">*</span> </h2></div>
                </div>
            </div>
                </div>
            <p id="finePrint">*All pricing is subjective and does not include regular data upkeep, regulation, administration. cathodation, and or authoritative assesment charges.</p>
        </div>
            

    </main>
    <?php 
    //calling our footer
    echo file_get_contents("foot.php");
    ?>
    
	<script type="text/javascript" src=""></script>
</body>

</html>