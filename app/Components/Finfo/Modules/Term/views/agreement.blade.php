@extends('resources.views.templates.finfo.frontend-temp')
@section('seo')
<title>FINFO &#8211; Investor Engagement Matters</title>
<meta name="description" content="Subscription Agreement">
<meta name="keywords" content="Subscription Agreement">
@stop

@section('style')
    <style id="responsive-style-inline-css" type="text/css">
h1{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:40px;line-height:38px;letter-spacing:0px;}h2{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:60px;line-height:65px;letter-spacing:0px;}h3, h3.wpb_accordion_header,h3.wpb_toggle_header,.woocommerce-loop-product__title{color:rgb(0,0,0);font-family:Poppins;font-weight:300;font-style:normal;font-size:50px;line-height:55px;letter-spacing:0px;}h4{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:40px;line-height:45px;letter-spacing:0px;}h5{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:30px;line-height:35px;letter-spacing:0px;}h6{color:rgb(0,0,0);font-family:Poppins;font-weight:500;font-style:normal;font-size:20px;line-height:25px;letter-spacing:0px;}p{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:14px;line-height:22px;letter-spacing:0px;}a{color:rgb(0,0,0);font-family:Poppins;font-weight:500;font-style:normal;font-size:14px;line-height:14px;letter-spacing:0px;}.layout{padding-top:0.0px;padding-bottom:0.0px;width:100%;}main{padding-top:0.0px;}header .content ul.icons-pack li.icon ,header.top-block .style-style2 .icons-pack .icon.notification-item{display:none;}header { top:0px;}header[class *= "side-" ]{width: 14%;;}header:not(.top-block) .top nav > ul > li .menu-title .icon , header.side-classic .side nav > ul > li > a .menu-title .icon, header.side-classic.standard-mode .style-center nav > ul > li > a .menu-title .icon, .gather-overlay .navigation li a span.icon, header.top-block.header-style1 .navigation > ul > li > a span.icon, header:not(.top-block) .top nav > ul > li .hover-effect .icon {display:none;} header:not(.top-block) .top nav > ul > li .menu-title .title, header.side-classic .side nav > ul > li > a .menu-title .title, header:not(.top-block) .top nav > ul > li .hover-effect .title {display:inline-block;}.activeMenu{ color:rgb(254,87,34) !important;}header a, header .navigation a, header .navigation, .gather-overlay .menu a, header.side-classic div.footer .footer-content .copyright p{ color:rgb(58,82,106);font-family:Poppins;font-weight:500;font-style:normal;font-size:13px;letter-spacing:0px;line-height : 1.5em;}header .icons-pack a{color:rgb(58,82,106)}header .navigation .separator a {background-color:rgba(58,82,106,0.5);;}header .icons-pack .elem-container .title-content{color:rgb(58,82,106);}.top-classic .navigation .menu-separator,.top-logotop .navigation .menu-separator{ background-color:rgb(254,87,34);}.top-classic:not(.header-clone) .style-wireframe .navigation .menu-separator{ background-color:rgb(58,82,106);}header.top-block .icons-pack li .elem-container,header .top .icons-pack .icon span,header.top-block .icons-pack li .title-content .icon,header.top-modern .icons-pack li .title-content .icon,header .icons-pack a{ font-size:18px;}.gather-btn .gather-menu-icon,header .icons-pack a.shopcart .icon-shopcart2,header .icons-pack a.shopcart .icon-shopping-cart{font-size:21px;}header .icons-pack .shopcart-item .number{color:rgb(58,82,106);background-color:rgb(254,87,34);}.layout-container .business{display:none;}header.top-classic:not(.header-clone) .content:not(.style-wireframe) nav > ul > li:hover > a .menu-title , header.top-classic:not(.header-clone) .content:not(.style-wireframe) nav > ul > li:hover > a .menu-title:after{ color:rgb(254,87,34);} .top-classic .style-wireframe .navigation > ul > li:hover .menu-separator{ background-color:rgb(254,87,34);} header.top-classic .icons-pack .icon:hover { color:rgb(254,87,34);}header.top-modern .btn-1b:after { background:rgb(58,82,106);}header.top-modern .btn-1b:active{ background:rgb(58,82,106);}header.top-modern nav > ul> li, header.top-modern .icons-pack li, header.top-modern .first-part{ border-right: 1px solid rgba(58,82,106,0.3);;}header.top-modern .business{ border-bottom: 1px solid rgba(58,82,106,0.3);;}header.top-modern .business, header.top-modern .business a{ color:rgb(58,82,106);}header.side-classic nav > ul > li:hover > a, header.side-classic.standard-mode .icons-holder ul.icons-pack li:hover a, header.side-classic.standard-mode .footer-socials li:hover a, header.side-classic nav > ul > li.has-dropdown:not(.megamenu):hover > a, header.side-classic nav > ul > li:hover > a > .menu-title span, header.side-classic .footer-socials li a .hover, header.side-classic .icons-pack li a .hover, header.side-modern .icons-pack li a span.hover, header.side-modern .nav-modern-button span.hover, header.side-modern .footer-socials span.hover, header.side-classic nav > ul > li.has-dropdown:not(.megamenu) .dropdown a:hover .menu-title span, header.side-classic nav > ul > li > ul li.has-dropdown:not(.megamenu):hover > a .menu-title span{ color:rgb(254,87,34);border-color:rgb(254,87,34);}header.side-classic div.footer ul li.info .footer-content span, header.side-classic .icons-pack li.search .search-form input{ color:rgb(58,82,106);}header.side-classic div.footer ul, header.side-classic div.footer ul li, header.side-classic .icons-holder{ border-color:rgb(58,82,106);}header.side-classic .icons-holder li hr{ background-color:rgb(58,82,106);}header .side .footer .copyright p{ color:rgb(58,82,106);}header .color-overlay, header.side-modern .footer .info .footer-content .copyright, header.side-modern .footer .info .footer-content .footer-socials, header.side-modern .search-form input[type="text"]{background-color: rgb(255,255,255);}header:not(.header-clone) > .color-overlay{}.second-header-bg {}header nav.navigation li.megamenu > .dropdown, header nav.navigation li.has-dropdown > .dropdown{ display : table; position: absolute; top:85px;}header nav.navigation li.megamenu > .dropdown > .megamenu-dropdown-overlay, .gather-overlay nav li.megamenu > .dropdown > .megamenu-dropdown-overlay, header nav > ul > li.has-dropdown:not(.megamenu) ul .megamenu-dropdown-overlay{ background-color:rgba(255,255,255,.8);}header nav.navigation > ul > li.megamenu > ul > li > a{ color:rgb(200,200,200);}header[class *= "top-"]:not(.right) nav.navigation li.megamenu > ul.dropdown:not(.side-line), header[class *= "top-"]:not(.right) nav.navigation > ul > li.has-dropdown > ul.dropdown:not(.side-line){border-top:3px solid rgba(63,63,63,1);}header.top nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line, header.top nav.navigation li.megamenu > .dropdown.side-line, .gather-overlay nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line, .gather-overlay nav.navigation li.megamenu > .dropdown.side-line{ border-left: 3px solid rgba(63,63,63,1);}header.top nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line li:after, .gather-overlay nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line li:after{ background-color:rgba(0,0,0,0.3);;}header[class *= "top-"]:not(.right) nav.navigation li.megamenu > .dropdown,header[class *= "top-"]:not(.right) nav.navigation li.has-dropdown > .dropdown{left: 0;}header[class *= "top-"] nav .dropdown a, header[class *= "side-"] nav .dropdown a, .gather-overlay nav .dropdown a{ font-size:12px;}.gather-overlay nav.navigation li.megamenu > .dropdown, .gather-overlay nav.navigation li.has-dropdown > .dropdown{ background-color:rgba(255,255,255,.8);display : table; left: 0; position: absolute; top: 150%; }header.left nav.navigation > ul > li.has-dropdown > .dropdown .megamenu-dropdown-overlay, header.side-modern .side.style-style2 nav > ul > li .megamenu-dropdown-overlay, header.side-modern .side.style-style1 nav > ul .megamenu-dropdown-overlay, header.side-modern .style-style1.side nav ul li{ background-color:rgba(255,255,255,.8);}header.side-modern .style-style1.side nav ul li, header.side-modern .style-style1.side nav.navigation > ul > li.has-dropdown .dropdown{ border-color:rgba(0,0,0,0.3);;color:rgb(0,0,0);}header nav.navigation .dropdown a, header.side-modern nav.navigation a, .gather-overlay nav.navigation .dropdown a{ color:rgb(0,0,0);position: relative !important; width: auto !important;}header .top nav > ul > li > ul li:hover > a .menu-title span, header .top nav > ul > li .dropdown a:hover .menu-title span, .gather-overlay nav > ul > li > ul li:hover > a .menu-title span, .gather-overlay nav > ul > li .dropdown a:hover .menu-title span, header.side-classic nav > ul > li > ul li:hover > a .menu-title span, header.side-classic nav > ul > li .dropdown a:hover .menu-title span, header.side-modern .side.style-style2 nav.navigation ul li a:hover{ color:rgba(63,63,63,1);border-color:rgba(63,63,63,1);}header.side-modern .side.style-style1 nav.navigation ul li:hover{ background-color:rgba(63,63,63,1);}.layout-container> .color-overlay,.layout-container> .texture-overlay,.layout-container > .bg-image { display:none; }.layout-container > .color-overlay.image-type,.layout-container> .bg-image { display:none; }.layout-container > .color-overlay.texture-type,.layout-container> .texture-overlay{ display:none; }.layout-container> .color-overlay.color-type {background-color:#FFF;}.layout-container> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}.layout-container> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1.png);}footer> .color-overlay,footer> .texture-overlay,footer > .bg-image { display:none; }footer> .color-overlay.color-type { display:none; }footer > .color-overlay.texture-type,footer> .texture-overlay{ display:none; }footer> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}footer> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1-2.png);}main .content .color-overlay.color-type { display:none }main .content .color-overlay.color-type { background-color: #FFF;}main .content { padding:0px;}main #content { margin-left: auto; margin-right: auto; }footer {width: 100% ; margin-top:0.0px; visibility: hidden; display: block;}footer .content{width:70%;}#footer-bottom .social-icons span a,#footer-bottom .go-to-top a,#footer-bottom p{color:rgba(255,255,255,0.71)}footer.footer-default .footer-widgets {background-color:rgb(0,82,147);overflow: hidden;}footer .widget-area {height:314px;}footer hr.footer-separator{height:0px;background-color:rgba(137, 137, 137, 0)}footer.footer-default .widget-area.classicStyle.border.boxed div[class*="col-"]{height:194px;}footer.footer-default .widget-area.classicStyle.border.full div[class*="col-"]{height :314px;padding : 45px 30px;}footer.footer-default #footer-bottom{background-color:rgb(0,82,147);}#footer-bottom{height:83px;}#footer-bottom .social-icons > span:not(.go-to-top){display:none;}#footer-bottom .copyright{display:none;}#footer-bottom .logo{opacity:1.0;}#footer-bottom .logo{display:none;}#footer-bottom {display:none;}.sidebar.box .widget> .color-overlay,.sidebar.box .widget> .texture-overlay,.sidebar.box .widget > .bg-image { display:none; }.sidebar.box .widget > .color-overlay.image-type,.sidebar.box .widget> .bg-image { display:none; }.sidebar.box .widget > .color-overlay.texture-type,.sidebar.box .widget> .texture-overlay{ display:none; }.sidebar.box .widget> .color-overlay.color-type {background-color:#FFF;}.sidebar.box .widget> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}.sidebar.box .widget> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1-4.png);}.sidebar> .color-overlay,.sidebar> .texture-overlay,.sidebar > .bg-image { display:none; }.sidebar > .color-overlay.image-type,.sidebar> .bg-image { display:none; }.sidebar > .color-overlay.texture-type,.sidebar> .texture-overlay{ display:none; }.sidebar> .color-overlay.color-type {background-color:#FFF;}.sidebar> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}.sidebar> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1-4.png);}.sidebar.box .widget .color-overlay, .sidebar.box .widget .texture-overlay, .sidebar.box .widget .bg-image{ display:none;}.dark-sidebar .widget-contact-info-content, .dark .widget-contact-info-content{ background:url(http://finfo-solution.wizwerx.com/wp-content/themes/massive-dynamic/assets/img/map-dark.png)no-repeat 10px 15px;}.light-sidebar .widget-contact-info-content, .light .widget-contact-info-content{ background:url(http://finfo-solution.wizwerx.com/wp-content/themes/massive-dynamic/assets/img/map-light.png)no-repeat 10px 15px;}.layout-container .business { background:rgb(82,82,82);top:0px;height: 36px;}.layout-container .business,.layout-container .business a { color:rgba(255,255,255,1);}header { margin-top: 0 }.box_size{ width:65%}.box_size_container{ width:65%}.widget a, .widget p, .widget span:not(.icon-caret-right)/*:not(.star-rating span)*/{ font-family:Poppins;}.loop-post-content .post-title:hover{ color:rgba(0,0,0,0.8);;}.woocommerce ul.product_list_widget li span:not(.star-rating span){ font-family:Poppins;}.notification-center .post .date .day.accent-color, #notification-tabs p.total, #notification-tabs p.total .amount, #notification-tabs .cart_list li .quantity, #notification-tabs .cart_list li .quantity .amount{ color :rgb(10,161,220);}.notification-center span, .notification-center a, .notification-center p, #notification-tabs #result-container .search-title, #notification-tabs #result-container .more-result, #notification-tabs #result-container .item .title, #notification-tabs #search-input, #notification-tabs .cart_list li.empty, .notification-collapse{ font-family :Poppins;}.portfolio .accent-color, .portfolio .accent-color.more-project, .portfolio-carousel .accent-color.like:hover, .portfolio-carousel .buttons .sharing:hover{ color :rgb(204,162,107)}.portfolio-split .accent-color.like:hover, .portfolio-full .accent-color.like:hover{ background-color :rgb(204,162,107);border-color :rgb(204,162,107);color:#fff; }.portfolio .accent-color.more-project:after{ background-color :rgb(204,162,107)}.portfolio .accent-color.more-project:hover{ color :rgba(204,162,107,0.6);}.portfolio .category span { color :rgba(0,0,0,0.7);}.portfolio .buttons .sharing, .portfolio-carousel .buttons .like{ border-color:rgb(0,0,0);color: rgb(0,0,0); }.portfolio-split .buttons .sharing:hover, .portfolio-full .buttons .sharing:hover{ background-color:rgb(0,0,0);color: #fff; }.md-pixflow-slider .btn-container .shortcode-btn a.button{ font-family:Poppins;}.md-statistic .timer-holder .timer, .md-counter:not(.md-countbox):not(.md-counter-card) .timer, .img-box-fancy .image-box-fancy-title{ font-family:Poppins;letter-spacing:0px;}.process-panel-main-container .sub-title{ font-family:Poppins;font-weight:300;font-style:normal;letter-spacing:0px;}.error404 .item-setting, body:not(.compose-mode) .item-setting{display: none;}header.top-classic .style-none nav > ul > .item_button{color:rgb(0,0,0);}header.top-classic .style-none nav > ul > .item_button:hover{color:rgb(255,255,255);}header.top-classic .style-none nav > ul > .item_button.oval_outline-style a,header.top-classic .style-none nav > ul > .item_button.rectangle_outline-style a{border-color:rgb(255,255,255);}header.top-classic .style-none nav > ul > .item_button.oval-style a,header.top-classic .style-none nav > ul > .item_button.rectangle-style a{background-color:rgb(255,255,255);}h1{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:40px;line-height:38px;letter-spacing:0px;}h2{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:60px;line-height:65px;letter-spacing:0px;}h3, h3.wpb_accordion_header,h3.wpb_toggle_header,.woocommerce-loop-product__title{color:rgb(0,0,0);font-family:Poppins;font-weight:300;font-style:normal;font-size:50px;line-height:55px;letter-spacing:0px;}h4{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:40px;line-height:45px;letter-spacing:0px;}h5{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:30px;line-height:35px;letter-spacing:0px;}h6{color:rgb(0,0,0);font-family:Poppins;font-weight:500;font-style:normal;font-size:20px;line-height:25px;letter-spacing:0px;}p{color:rgb(0,0,0);font-family:Poppins;font-weight:400;font-style:normal;font-size:14px;line-height:22px;letter-spacing:0px;}a{color:rgb(0,0,0);font-family:Poppins;font-weight:500;font-style:normal;font-size:14px;line-height:14px;letter-spacing:0px;}.layout{padding-top:0.0px;padding-bottom:0.0px;width:100%;}main{padding-top:0.0px;}header .content ul.icons-pack li.icon ,header.top-block .style-style2 .icons-pack .icon.notification-item{display:none;}header { top:0px;}header[class *= "side-" ]{width: 14%;;}header:not(.top-block) .top nav > ul > li .menu-title .icon , header.side-classic .side nav > ul > li > a .menu-title .icon, header.side-classic.standard-mode .style-center nav > ul > li > a .menu-title .icon, .gather-overlay .navigation li a span.icon, header.top-block.header-style1 .navigation > ul > li > a span.icon, header:not(.top-block) .top nav > ul > li .hover-effect .icon {display:none;} header:not(.top-block) .top nav > ul > li .menu-title .title, header.side-classic .side nav > ul > li > a .menu-title .title, header:not(.top-block) .top nav > ul > li .hover-effect .title {display:inline-block;}.activeMenu{ color:rgb(254,87,34) !important;}header a, header .navigation a, header .navigation, .gather-overlay .menu a, header.side-classic div.footer .footer-content .copyright p{ color:rgb(58,82,106);font-family:Poppins;font-weight:500;font-style:normal;font-size:13px;letter-spacing:0px;line-height : 1.5em;}header .icons-pack a{color:rgb(58,82,106)}header .navigation .separator a {background-color:rgba(58,82,106,0.5);;}header .icons-pack .elem-container .title-content{color:rgb(58,82,106);}.top-classic .navigation .menu-separator,.top-logotop .navigation .menu-separator{ background-color:rgb(254,87,34);}.top-classic:not(.header-clone) .style-wireframe .navigation .menu-separator{ background-color:rgb(58,82,106);}header.top-block .icons-pack li .elem-container,header .top .icons-pack .icon span,header.top-block .icons-pack li .title-content .icon,header.top-modern .icons-pack li .title-content .icon,header .icons-pack a{ font-size:18px;}.gather-btn .gather-menu-icon,header .icons-pack a.shopcart .icon-shopcart2,header .icons-pack a.shopcart .icon-shopping-cart{font-size:21px;}header .icons-pack .shopcart-item .number{color:rgb(58,82,106);background-color:rgb(254,87,34);}.layout-container .business{display:none;}header.top-classic:not(.header-clone) .content:not(.style-wireframe) nav > ul > li:hover > a .menu-title , header.top-classic:not(.header-clone) .content:not(.style-wireframe) nav > ul > li:hover > a .menu-title:after{ color:rgb(254,87,34);} .top-classic .style-wireframe .navigation > ul > li:hover .menu-separator{ background-color:rgb(254,87,34);} header.top-classic .icons-pack .icon:hover { color:rgb(254,87,34);}header.top-modern .btn-1b:after { background:rgb(58,82,106);}header.top-modern .btn-1b:active{ background:rgb(58,82,106);}header.top-modern nav > ul> li, header.top-modern .icons-pack li, header.top-modern .first-part{ border-right: 1px solid rgba(58,82,106,0.3);;}header.top-modern .business{ border-bottom: 1px solid rgba(58,82,106,0.3);;}header.top-modern .business, header.top-modern .business a{ color:rgb(58,82,106);}header.side-classic nav > ul > li:hover > a, header.side-classic.standard-mode .icons-holder ul.icons-pack li:hover a, header.side-classic.standard-mode .footer-socials li:hover a, header.side-classic nav > ul > li.has-dropdown:not(.megamenu):hover > a, header.side-classic nav > ul > li:hover > a > .menu-title span, header.side-classic .footer-socials li a .hover, header.side-classic .icons-pack li a .hover, header.side-modern .icons-pack li a span.hover, header.side-modern .nav-modern-button span.hover, header.side-modern .footer-socials span.hover, header.side-classic nav > ul > li.has-dropdown:not(.megamenu) .dropdown a:hover .menu-title span, header.side-classic nav > ul > li > ul li.has-dropdown:not(.megamenu):hover > a .menu-title span{ color:rgb(254,87,34);border-color:rgb(254,87,34);}header.side-classic div.footer ul li.info .footer-content span, header.side-classic .icons-pack li.search .search-form input{ color:rgb(58,82,106);}header.side-classic div.footer ul, header.side-classic div.footer ul li, header.side-classic .icons-holder{ border-color:rgb(58,82,106);}header.side-classic .icons-holder li hr{ background-color:rgb(58,82,106);}header .side .footer .copyright p{ color:rgb(58,82,106);}header .color-overlay, header.side-modern .footer .info .footer-content .copyright, header.side-modern .footer .info .footer-content .footer-socials, header.side-modern .search-form input[type="text"]{background-color: rgb(255,255,255);}header:not(.header-clone) > .color-overlay{}.second-header-bg {}header nav.navigation li.megamenu > .dropdown, header nav.navigation li.has-dropdown > .dropdown{ display : table; position: absolute; top:85px;}header nav.navigation li.megamenu > .dropdown > .megamenu-dropdown-overlay, .gather-overlay nav li.megamenu > .dropdown > .megamenu-dropdown-overlay, header nav > ul > li.has-dropdown:not(.megamenu) ul .megamenu-dropdown-overlay{ background-color:rgba(255,255,255,.8);}header nav.navigation > ul > li.megamenu > ul > li > a{ color:rgb(200,200,200);}header[class *= "top-"]:not(.right) nav.navigation li.megamenu > ul.dropdown:not(.side-line), header[class *= "top-"]:not(.right) nav.navigation > ul > li.has-dropdown > ul.dropdown:not(.side-line){border-top:3px solid rgba(63,63,63,1);}header.top nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line, header.top nav.navigation li.megamenu > .dropdown.side-line, .gather-overlay nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line, .gather-overlay nav.navigation li.megamenu > .dropdown.side-line{ border-left: 3px solid rgba(63,63,63,1);}header.top nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line li:after, .gather-overlay nav.navigation > ul > li.has-dropdown:not(.megamenu) .dropdown.side-line li:after{ background-color:rgba(0,0,0,0.3);;}header[class *= "top-"]:not(.right) nav.navigation li.megamenu > .dropdown,header[class *= "top-"]:not(.right) nav.navigation li.has-dropdown > .dropdown{left: 0;}header[class *= "top-"] nav .dropdown a, header[class *= "side-"] nav .dropdown a, .gather-overlay nav .dropdown a{ font-size:12px;}.gather-overlay nav.navigation li.megamenu > .dropdown, .gather-overlay nav.navigation li.has-dropdown > .dropdown{ background-color:rgba(255,255,255,.8);display : table; left: 0; position: absolute; top: 150%; }header.left nav.navigation > ul > li.has-dropdown > .dropdown .megamenu-dropdown-overlay, header.side-modern .side.style-style2 nav > ul > li .megamenu-dropdown-overlay, header.side-modern .side.style-style1 nav > ul .megamenu-dropdown-overlay, header.side-modern .style-style1.side nav ul li{ background-color:rgba(255,255,255,.8);}header.side-modern .style-style1.side nav ul li, header.side-modern .style-style1.side nav.navigation > ul > li.has-dropdown .dropdown{ border-color:rgba(0,0,0,0.3);;color:rgb(0,0,0);}header nav.navigation .dropdown a, header.side-modern nav.navigation a, .gather-overlay nav.navigation .dropdown a{ color:rgb(0,0,0);position: relative !important; width: auto !important;}header .top nav > ul > li > ul li:hover > a .menu-title span, header .top nav > ul > li .dropdown a:hover .menu-title span, .gather-overlay nav > ul > li > ul li:hover > a .menu-title span, .gather-overlay nav > ul > li .dropdown a:hover .menu-title span, header.side-classic nav > ul > li > ul li:hover > a .menu-title span, header.side-classic nav > ul > li .dropdown a:hover .menu-title span, header.side-modern .side.style-style2 nav.navigation ul li a:hover{ color:rgba(63,63,63,1);border-color:rgba(63,63,63,1);}header.side-modern .side.style-style1 nav.navigation ul li:hover{ background-color:rgba(63,63,63,1);}.layout-container> .color-overlay,.layout-container> .texture-overlay,.layout-container > .bg-image { display:none; }.layout-container > .color-overlay.image-type,.layout-container> .bg-image { display:none; }.layout-container > .color-overlay.texture-type,.layout-container> .texture-overlay{ display:none; }.layout-container> .color-overlay.color-type {background-color:#FFF;}.layout-container> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}.layout-container> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1.png);}footer> .color-overlay,footer> .texture-overlay,footer > .bg-image { display:none; }footer> .color-overlay.color-type { display:none; }footer > .color-overlay.texture-type,footer> .texture-overlay{ display:none; }footer> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}footer> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1-2.png);}main .content .color-overlay.color-type { display:none }main .content .color-overlay.color-type { background-color: #FFF;}main .content { padding:0px;}main #content { margin-left: auto; margin-right: auto; }footer {width: 100% ; margin-top:0.0px; visibility: hidden; display: block;}footer .content{width:70%;}#footer-bottom .social-icons span a,#footer-bottom .go-to-top a,#footer-bottom p{color:rgba(255,255,255,0.71)}footer.footer-default .footer-widgets {background-color:rgb(0,82,147);overflow: hidden;}footer .widget-area {height:314px;}footer hr.footer-separator{height:0px;background-color:rgba(137, 137, 137, 0)}footer.footer-default .widget-area.classicStyle.border.boxed div[class*="col-"]{height:194px;}footer.footer-default .widget-area.classicStyle.border.full div[class*="col-"]{height :314px;padding : 45px 30px;}footer.footer-default #footer-bottom{background-color:rgb(0,82,147);}#footer-bottom{height:83px;}#footer-bottom .social-icons > span:not(.go-to-top){display:none;}#footer-bottom .copyright{display:none;}#footer-bottom .logo{opacity:1.0;}#footer-bottom .logo{display:none;}#footer-bottom {display:none;}.sidebar.box .widget> .color-overlay,.sidebar.box .widget> .texture-overlay,.sidebar.box .widget > .bg-image { display:none; }.sidebar.box .widget > .color-overlay.image-type,.sidebar.box .widget> .bg-image { display:none; }.sidebar.box .widget > .color-overlay.texture-type,.sidebar.box .widget> .texture-overlay{ display:none; }.sidebar.box .widget> .color-overlay.color-type {background-color:#FFF;}.sidebar.box .widget> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}.sidebar.box .widget> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1-4.png);}.sidebar> .color-overlay,.sidebar> .texture-overlay,.sidebar > .bg-image { display:none; }.sidebar > .color-overlay.image-type,.sidebar> .bg-image { display:none; }.sidebar > .color-overlay.texture-type,.sidebar> .texture-overlay{ display:none; }.sidebar> .color-overlay.color-type {background-color:#FFF;}.sidebar> .bg-image { background-repeat:no-repeat;background-attachment:fixed;background-position:center top;background-size: cover;opacity:1;}.sidebar> .texture-overlay { opacity:0.5;background-image: url(http://finfo-solution.wizwerx.com/wp-content/uploads/2017/07/1-4.png);}.sidebar.box .widget .color-overlay, .sidebar.box .widget .texture-overlay, .sidebar.box .widget .bg-image{ display:none;}.dark-sidebar .widget-contact-info-content, .dark .widget-contact-info-content{ background:url(http://finfo-solution.wizwerx.com/wp-content/themes/massive-dynamic/assets/img/map-dark.png)no-repeat 10px 15px;}.light-sidebar .widget-contact-info-content, .light .widget-contact-info-content{ background:url(http://finfo-solution.wizwerx.com/wp-content/themes/massive-dynamic/assets/img/map-light.png)no-repeat 10px 15px;}.layout-container .business { background:rgb(82,82,82);top:0px;height: 36px;}.layout-container .business,.layout-container .business a { color:rgba(255,255,255,1);}header { margin-top: 0 }.box_size{ width:65%}.box_size_container{ width:65%}.widget a, .widget p, .widget span:not(.icon-caret-right)/*:not(.star-rating span)*/{ font-family:Poppins;}.loop-post-content .post-title:hover{ color:rgba(0,0,0,0.8);;}.woocommerce ul.product_list_widget li span:not(.star-rating span){ font-family:Poppins;}.notification-center .post .date .day.accent-color, #notification-tabs p.total, #notification-tabs p.total .amount, #notification-tabs .cart_list li .quantity, #notification-tabs .cart_list li .quantity .amount{ color :rgb(10,161,220);}.notification-center span, .notification-center a, .notification-center p, #notification-tabs #result-container .search-title, #notification-tabs #result-container .more-result, #notification-tabs #result-container .item .title, #notification-tabs #search-input, #notification-tabs .cart_list li.empty, .notification-collapse{ font-family :Poppins;}.portfolio .accent-color, .portfolio .accent-color.more-project, .portfolio-carousel .accent-color.like:hover, .portfolio-carousel .buttons .sharing:hover{ color :rgb(204,162,107)}.portfolio-split .accent-color.like:hover, .portfolio-full .accent-color.like:hover{ background-color :rgb(204,162,107);border-color :rgb(204,162,107);color:#fff; }.portfolio .accent-color.more-project:after{ background-color :rgb(204,162,107)}.portfolio .accent-color.more-project:hover{ color :rgba(204,162,107,0.6);}.portfolio .category span { color :rgba(0,0,0,0.7);}.portfolio .buttons .sharing, .portfolio-carousel .buttons .like{ border-color:rgb(0,0,0);color: rgb(0,0,0); }.portfolio-split .buttons .sharing:hover, .portfolio-full .buttons .sharing:hover{ background-color:rgb(0,0,0);color: #fff; }.md-pixflow-slider .btn-container .shortcode-btn a.button{ font-family:Poppins;}.md-statistic .timer-holder .timer, .md-counter:not(.md-countbox):not(.md-counter-card) .timer, .img-box-fancy .image-box-fancy-title{ font-family:Poppins;letter-spacing:0px;}.process-panel-main-container .sub-title{ font-family:Poppins;font-weight:300;font-style:normal;letter-spacing:0px;}.error404 .item-setting, body:not(.compose-mode) .item-setting{display: none;}header.top-classic .style-none nav > ul > .item_button{color:rgb(0,0,0);}header.top-classic .style-none nav > ul > .item_button:hover{color:rgb(255,255,255);}header.top-classic .style-none nav > ul > .item_button.oval_outline-style a,header.top-classic .style-none nav > ul > .item_button.rectangle_outline-style a{border-color:rgb(255,255,255);}header.top-classic .style-none nav > ul > .item_button.oval_outline-style:hover a,header.top-classic .style-none nav > ul > .item_button.rectangle_outline-style:hover a{border-color:rgb(0,0,0);background-color:rgb(0,0,0)}header.top-classic .style-none nav > ul > .item_button.oval-style:hover a,header.top-classic .style-none nav > ul > .item_button.rectangle-style:hover a{background-color:rgb(0,0,0)}body.massive-rtl{font-family:Poppins;}
.dark-content .widget p{ color:#212121;}.dark-content .widget a{ color:#03a9f4; font-size:15px;}.dark-content .widget a:hover{ color:#005293;}.dark-content ol{ padding-left:0px; font-size:15px; list-style-type:none; counter-reset:item;}.dark-content li{ display:table; counter-increment:item;}.dark-content li:before{ content:counters(item, ".") ". "; display:table-cell; padding-right:0.6em;}.dark-content li ol > li:before{ content:counters(item, ".") " ";}.iconbox-side2.style2 .image-container .iconbox_side2_image{ width:60px !important; height:60px !important;}.finfo-checklist-space .iconbox-side2.style2{padding:0px !important;}.finfo-checklist-space .iconbox-side2.style2 .image-container .iconbox_side2_image{ width:30px !important; height:30px !important;}.finfo-checklist-space .iconbox-side2.style2 .iconbox-content{max-width:650px;}.list-shortcode ul li p{font-size:20px;}.price-table-fix-height .price-table-container .description{min-height:340px;}.feature-desc .widget p{ font-size:22px; font-weight:400; text-align:center; line-height:35px; padding-top:30px; padding-bottom:55px;}.loc-address .widget p{ color:#3a526a; font-size:18px; font-weight:400; text-align:center; padding-left:10px; padding-right:10px;}.widget-area .widget-title{font-weight:500;}.widget p{ font-size:15px; color:#fff; padding-bottom:30px; line-height:25px;}.widget ul{list-style:none;}.widget li{margin-bottom:10px;}.widget ul li a{font-size:15px;}.member-content .widget p.member-name{ font-size:27px; line-height:34px; letter-spacing:0px; margin-bottom:3px; padding-bottom:0; color:#3a526a; font-weight:600;}.member-content .widget p.member-designation{ font-size:16px; line-height:24px; color:#3a3a3a; font-weight:500; text-transform:uppercase;}body:not(.compose-mode) header:not(.retina-screen-header) .logo,body.compose-mode.responsive-mode header:not(.retina-screen-header) .logo{ width:15.7% !important; min-width:80px;}.form-container-modern .two-col .input{ width:calc(100% /2 - 17px); max-width:none;}.form-container-modern .dropdown.input .input__label-content{font-size:12px;}.form-container-modern .dropdown.input label{ display:block; color:rgba(255,255,255,.7); text-align:left;}#home-package-select:focus{outline:none !important;}#home-package-select option{color:#000 !important;}#home-package-select{ width:100%; background:transparent; border:none; color:#FFF; border-bottom:3px solid rgba(255,255,255,.7); margin-top:14px;}span.wpcf7-not-valid-tip{color:#f44336 !important;}@media (max-width:800px){ .page-id-193 .img-box-slider .imgBox-image:not(.cover){background-size:auto !important;}}@media (max-width:767px){ .form-container-modern .two-col .input{width:100%;}}
</style>
@stop

@section('content')

    <div id="rowCustom-599be636729f2" class="no-bg-image vc_row wpb_row mBuilder-element vc_row wpb_row mBuilder-element    sectionOverlay vc_general vc_parallax vc_parallax- full_size " data-mbuilder-id="1" data-col-layout="12/12" data-bgcolor=" rgb(238, 238, 238)">
        
        <script type="text/javascript">"use strict";var
$=jQuery;$(document).ready(function(){if(typeof
$!='function'){$=jQuery;}var
isChrome=window.chrome,$rowCustom_599be636729f2=$("#rowCustom-599be636729f2");$rowCustom_599be636729f2.find('.row-image').remove();$rowCustom_599be636729f2.append('<div class="row-image row-image-normal "> </div>');if(!("")){$rowCustom_599be636729f2.find('.row-image').remove();}if(typeof
pixflow_fitRowToHeight=='function'){pixflow_fitRowToHeight();}if(isChrome){$rowCustom_599be636729f2.find(".row-image-fixed").append('<Style> .row-image-fixed:after{position:fixed; background-attachment:inherit;}</style>');}});</script>


        <!-- Set background image -->
        <style>    #rowCustom-599be636729f2 .row-image{    background-position:center center;}    </style>

                        <script type="text/javascript">"use strict";var
$=jQuery;var
$rowCustom_599be636729f2=$('#rowCustom-599be636729f2');if($rowCustom_599be636729f2.find('.sloped-edge').length){$rowCustom_599be636729f2.find('.sloped-edge').remove();}</script>
            
                        <div class="wrap clearfix box_size_container">

            
    <div class="wpb_column vc_column_container    col-sm-12">
        <div class="vc_column-inner md_col-599be63672d07">
            <div class="wpb_wrapper">
                <style data-type="mBuilderInternal"> div.vc_column_container>.vc_column-inner.md_col-599be63672d07{}</style>
                    <style> .md_text_style-599be636730cb .md-text-title{color:rgb(33, 33, 33);}.md_text_style-599be636730cb{text-align:left;}.md_text_style-599be636730cb .md-text-title *{line-height:25px; font-family:Poppins; font-style:bold; font-weight:600;}.md_text_style-599be636730cb .md-text-title{font-size:32px; line-height:25px; letter-spacing:0px; margin-bottom:39px; transition:all .3s cubic-bezier(0.215, 0.61, 0.355, 1) ; font-family:Poppins; font-style:bold; font-weight:600;}.md_text_style-599be636730cb .md-text-title:not(.title-slider):hover{letter-spacing:0px;}.md_text_style-599be636730cb .md-text-title-separator{margin-bottom:10px ; width:110px; border-top:5px solid rgb(0, 255, 153); margin-left:0; margin-right:auto;}.md_text_style-599be636730cb .md-text-content{margin-bottom:25px;}.md_text_style-599be636730cb .md-text-content p{color:rgba(20,20,20,1); font-size:14px; line-height:21px; font-family:Roboto; font-style:regular; font-weight:400;}</style>
        <div class="md-text-container md_text_style-599be636730cb  md-align-center wpb_wrapper wpb_md_text_wrapper ui-md_text">
        <div class="md-text gizmo-container">

                                            <div class="md-text-title inline-editor-title "><div><span style="font-size: 30px;">SUBSCRIPTION AGREEMENT</span></div></div>
                
            
                        <div class="md-text-content inline-editor  without-content"><p></p></div>
            
            
        </div>

    </div>
    <script type="text/javascript">if(typeof
pixflow_title_slider=='function'){pixflow_title_slider();}</script>
                </div>
        </div>
    </div>
    
            <script>var
$=jQuery;var
$rowCustom_599be636729f2=$('#rowCustom-599be636729f2');if("1"){$rowCustom_599be636729f2.find('> .wrap').addClass('box_size_container');$rowCustom_599be636729f2.find('> .wrap').addClass('box_size_container');}else{$rowCustom_599be636729f2.find('> .wrap').removeClass('box_size_container');$rowCustom_599be636729f2.find('> .wrap').removeClass('box_size_container');}$('#rowCustom-599be636729f2').find('.row-videobg').not('.row-videobg[data-row-id="rowCustom-599be636729f2"]').remove();</script>

        </div> <!-- End wrap -->

        <style>    #rowCustom-599be636729f2{  margin-top:0px;margin-bottom:0px;} #rowCustom-599be636729f2{  padding-top:200px;padding-bottom:85px;padding-right:0px;padding-left:0px;} .sectionOverlay.box_size{ width:65%} .sectionOverlay .box_size_container{ width:65%} #rowCustom-599be636729f2:after{  background-color:rgb(238, 238, 238)}   </style>
    </div> <!-- End main row -->
    
    <div id="rowCustom-599be6367513c" class="no-bg-image vc_row wpb_row mBuilder-element vc_row wpb_row mBuilder-element    sectionOverlay vc_general vc_parallax vc_parallax- full_size " data-mbuilder-id="2" data-col-layout="12/12" data-bgcolor=" rgba(255,255,255,1)">
        
        <script type="text/javascript">"use strict";var
$=jQuery;$(document).ready(function(){if(typeof
$!='function'){$=jQuery;}var
isChrome=window.chrome,$rowCustom_599be6367513c=$("#rowCustom-599be6367513c");$rowCustom_599be6367513c.find('.row-image').remove();$rowCustom_599be6367513c.append('<div class="row-image row-image-normal "> </div>');if(!("")){$rowCustom_599be6367513c.find('.row-image').remove();}if(typeof
pixflow_fitRowToHeight=='function'){pixflow_fitRowToHeight();}if(isChrome){$rowCustom_599be6367513c.find(".row-image-fixed").append('<Style> .row-image-fixed:after{position:fixed; background-attachment:inherit;}</style>');}});</script>

        <style>    #rowCustom-599be6367513c .row-image {background-position:center center;} </style>

                        <script type="text/javascript">"use strict";var
$=jQuery;var
$rowCustom_599be6367513c=$('#rowCustom-599be6367513c');if($rowCustom_599be6367513c.find('.sloped-edge').length){$rowCustom_599be6367513c.find('.sloped-edge').remove();}</script>
            
                        <div class="wrap clearfix box_size_container">

            
    <div class="wpb_column vc_column_container    col-sm-12">
        <div class="vc_column-inner md_col-599be63675b09">
            <div class="wpb_wrapper">
                <style data-type="mBuilderInternal"> div.vc_column_container>.vc_column-inner.md_col-599be63675b09{}</style>
                <div class="vc_wp_text wpb_content_element dark-content"><div class="widget widget_text">
                <div class="textwidget">
                <p style="margin-bottom: 0;"><b>BACKGROUND</b></p>
                <style>.upper-latin-listing li:before { content: "(" counter(item,upper-latin) ")"; }</style>
                <style>.lower-latin-listing li:before { content: "(" counter(item,lower-latin) ")" !important; }</style>
								<ol class="upper-latin-listing" style="margin-bottom: 30px; text-align: left;">
									<li>Finfo has developed certain software applications and platforms which it makes available to subscribers via the internet on a pay-per- use basis for the purpose of allowing subscribers to create their own investor relations microsites.</li>
									<li>The Customer wishes to use Finfo&#39;s service in its business operations.</li>
									<li>Finfo has agreed to provide and the Customer has agreed to take and pay for Finfo&#39;s services subject to the terms and conditions of this Subscription Agreement.</li>
								</ol>
								<p>
                	<b>Agreed terms</b> <br><br>
                	By signing at the end of this agreement, the Customer hereby agrees to be bound by this Subscription Agreement (“<b>Agreement</b>”) as well as Finfo’s Website Terms of Use (including Finfo’s Privacy Policy) (collectively “<b>Web Terms</b>”) set out in Schedule 3:
                </p>
<style>.main-ol > li {margin-bottom: 30px;} </style>
<ol class="main-ol" style="text-align: left;">
	
	<li>
		<b>Interpretation</b>
		<ol>
			<li>All defined terms in the Web Terms apply to this Agreement unless specifically defined in this Agreement.</li>
			<li>
				The definitions and rules of interpretation in this clause apply in this Agreement. <br><br>
				<b>Account</b> means the registered account of a Customer who has purchased a Subscription. <br><br>
				<b>Authorised Users</b> means those employees, agents and independent contractors of the Customer who are authorised by the Customer to use the Services and the Documentation. <br><br>
				<b>Confidential Information</b> means information that has been marked or identified as “confidential”, “proprietary” or similar description at the time of disclosure, or information that is generally understood to be confidential due to the nature of the information or circumstances under which it is provided. <br><br>
				<b>Customer Data</b> means data (including personal data as defined in the PDPA) provided by the Customer or its Authorised Users for the purpose of using the Services. <br><br>
				<b>Documentation</b> means the document(s) made available to the Customer by Finfo which sets out a description of the Services and the user instructions for the Services, as set out in Schedule 2. <br><br>
				<b>Effective Date</b> means the date of this Agreement. <br><br>
				<b>Initial Subscription Term</b> means the initial term of this Agreement as set out in Schedule 1. <br><br>
				<b>Materials</b> means the materials (including text, graphics, pictures, audio files, videos, code, financial information, charts, spreadsheets, prices and tables) published or to be published by the Customer on its Microsite. <br><br>
				<b>Microsite</b> means the Customer’s investor relations microsite created through the use of Finfo’s Services. <br><br>
				<b>PDPA</b> means the Singapore Personal Data Protection Act (No. 26 of 2012), as amended from time to time. <br><br>
				<b>Renewal Period</b> means the period described in clause 11.1. <br><br>
				<b>Services</b> means the services provided by Finfo to the Customer under this Agreement via the Site, as more particularly described in the Documentation, and which includes: <br><br>
				(a) the creation of Microsites using the Software; and <br><br>
				(b) Uploading Services, if purchased by the Customer in accordance with clause 6.1 and Schedule 1. <br><br>
				<b>Site</b> means <a href="{{ route('finfo.home') }}">{{ route('finfo.home') }}</a> (including all its sub-domains), or any other website notified to the Customer by Finfo from time to time. <br><br>
				<b>Software</b> means the online software applications provided by Finfo as part of the Services. <br><br>
				<b>Subscription</b> means the subscription purchased by the Customer as indicated in Schedule 1 which entitles Authorised Users to access and use the Services and the Documentation in accordance with this Agreement. <br><br>
				<b>Subscription Fees</b> means the subscription fees payable by the Customer to Finfo for the Subscription, as set out in Schedule 1. <br><br>
				<b>Subscription Term</b> has the meaning given in clause 11.1. <br><br>
				<b>Uploading Services</b> has the meaning given in clause 4.1. <br><br>
			</li>
			<li>Clause, schedule and paragraph headings shall not affect the interpretation of this Agreement.</li>
			<li>A person includes an individual, corporate or unincorporated body (whether or not having separate legal personality).</li>
		</ol>
	</li>
	<li>
		<b>Subscriptions</b>
		<ol>
			<li>Subject to the Customer purchasing the Subscription in accordance with clause 6.1, the restrictions set out in this clause 2 and the other terms and conditions of this Agreement, Finfo hereby grants to the Customer a non-exclusive, non-transferable right, without the right to grant sublicences, to permit the Authorised Users to access the Site and use the Services, Content and Documentation during the Subscription Term for the purposes of creating a Microsite.</li>
			<li>Finfo shall issue an Account and password to the Customer to enable the Customer to access the Site and use the Services, Content and Documentation.</li>
			<li>Finfo shall, as part of the Services, provide the Customer with Finfo’s standard customer support services between the hours of 9:00am and 5:00pm Singapore time, Monday to Friday, excluding public holidays in Singapore.</li>
			<li>The Customer shall prevent any unauthorised access to, or use of, the Site, Services, Software, Content and/or the Documentation and, in the event of any such unauthorised access or use, promptly notify Finfo.</li>
			<li>The Customer shall be bound by and responsible for, and Finfo shall be entitled to rely on, all communications transmitted through the use of the Customer’s Account, and all such communications shall be deemed to be communications made and issued by the Customer.</li>
			<li>Save to the extent permitted by applicable law, the Customer shall not:
				<ol class="lower-latin-listing">
					<li>attempt to copy, modify, duplicate, create derivative works from, frame, mirror, republish, download, display, transmit, or distribute all or any portion of the Site, Services, Software, Content, Linked Sites and/or Documentation (as applicable) in any form or media or by any means;</li>
					<li>attempt to de-compile, reverse compile, disassemble, reverse engineer or otherwise attempt to discover the source code of all or any part of the Software;</li>
					<li>access all or any part of the Site, Services, Software, Content and Documentation in order to build a product or service which competes with the Site, Services, Software, Content and/or the Documentation; or</li>
					<li>license, sell, rent, lease, transfer, assign, distribute, display, disclose, or otherwise commercially exploit, or otherwise make the Site, Services, Software, Content and/or Documentation available to any third party except the Authorised Users.</li>
				</ol>
			</li>
			<li>The rights provided under this clause 2 are granted to the Customer only, and shall not be considered granted to any subsidiary or holding company of the Customer.</li>
		</ol>
	</li>
	<li>
		<b>Customer data</b>
		<ol>
			<li>The Customer consents to Finfo’s use and disclosure of all Customer Data provided to Finfo for the purposes set out in Finfo’s Privacy Policy, which is hereby incorporated by reference into this Agreement and set out in Schedule 3.</li>
			<li>The Customer shall have sole responsibility for the legality, reliability, integrity, accuracy and quality of the Customer Data at all times.</li>
			<li>If Finfo processes any personal data on the Customer's behalf when performing its obligations under this Agreement, the Customer acknowledges and agrees that Finfo shall be a data intermediary within the meaning of the PDPA. The Customer:
				<ol class="lower-latin-listing">
					<li>agrees that the personal data may be transferred or stored outside Singapore in order to carry out the Services and Finfo&#39;s other obligations under this Agreement;</li>
					<li>shall, when providing the personal data of third parties to Finfo, be solely responsible for ensuring that the third parties have given their consent to Finfo’s collection, use, disclosure, processing, and transfer of their personal data as required by the PDPA;</li>
					<li>shall promptly notify Finfo if any third party mentioned in clause 3.3(b) withdraws his consent; and</li>
					<li>shall make reasonable efforts to ensure that the personal data provided to Finfo is up-to- date, accurate and complete.</li>
				</ol>
			</li>
		</ol>
	</li>
	<li>
		<b>Customer&#39;s obligations</b>
		<ol>
			<li>The Customer shall publish Materials on its Microsite:
				<ol class="lower-latin-listing">
					<li>directly, by using the Site, Services (including the Software), Content and Documentation; and/or</li>
					<li>by submitting Materials to Finfo by email, post or otherwise, and Finfo shall upload such Materials to the Microsite on the Customer’s behalf (“<b>Uploading Services</b>”), provided that the Customer has purchased Uploading Services as an additional service in accordance with clause 6.1 and Schedule 1.</li>
				</ol>
			</li>
			<li>The Customer shall:
				<ol class="lower-latin-listing">
					<li>comply with all applicable laws and regulations with respect to its activities under this Agreement; and</li>
					<li>ensure that the Authorised Users use the Site, Services, Software, Content and the Documentation in accordance with the terms and conditions of this Agreement, including the Web Terms set out in Schedule 3 and shall be responsible for any Authorised User&#39;s failure to do so.</li>
				</ol>
			</li>
			<li>The Customer shall not access, store, distribute or transmit any Materials during the course of its use of the Services that:
				<ol class="lower-latin-listing">
					<li>are inaccurate, misleading, libellous, defamatory, threatening, pornographic, an invasion of privacy, obscene, indecent, lewd, crude, abusive, improper, illegal, political, racist, religious, blasphemous, offensive or false;</li>
					<li>would infringe any intellectual property or other rights of a third party (including rights of a proprietary or equitable nature);</li>
					<li>would otherwise violate or encourage the violation of any law or regulation (including the Singapore Official Secrets Act (Cap.213)); or</li>
					<li>include programs that contain viruses, Trojan horses, worms, time bombs or any other programs, scripts or executable codes designed, or which have the tendency, to impair, interfere, interrupt, obstruct or prevent access to the operation and functionality of the Software, Services, or any other computer,</li>
					(individually and collectively “<b>Improper Works</b>”).
				</ol>
			</li>
			<li>The Customer agrees that by accessing, storing, distributing or transmitting any Materials through its use of the Services, the Customer:
				<ol class="lower-latin-listing">
					<li>automatically grants Finfo, its affiliates, subsidiaries and subcontractors (including its data and content hosting servers and delivery networks) a non- exclusive, royalty-free, irrevocable, perpetual and worldwide licence to access and use the Materials in connection with the provision of the Services;</li>
					<li>acknowledges and agrees that Finfo has no control over, and does not vet, screen, filter, censor or edit Materials published by the Customer on its Microsite, including where Finfo provides Uploading Services;</li>
					<li>acknowledges and agrees that Finfo shall not be responsible for the Customer’s compliance with any law or regulation in Singapore or elsewhere, in particular compliance with financial services regulations, in respect of Materials published by the Customer on its Microsite, including where Finfo provides Uploading Services;</li>
					<li>consents to the Materials being disclosed to the public through the Customer’s Microsite;</li>
					<li>represents and warrants that:
						<ol>
							<li>all Materials are the Customer’s own original works and creations and do not and will not infringe the copyright or any other intellectual property or other rights of any third party;</li>
							<li>none of the Materials are confidential; and</li>
							<li>none of the Materials constitute Improper Works, nor will they expose Finfo to any civil or criminal proceedings in Singapore or any part of the world.</li>
						</ol>
					</li>
				</ol>
			</li>
			<li>Notwithstanding the provisions in clause 4.4(b), Finfo at all times retains the sole discretion to remove any Materials without assigning any reason whatsoever. Without limiting the foregoing, Finfo may monitor the Services for Improper Works (but is not obliged to do so) and reserves the right to remove any Materials which Finfo believes are Improper Works, or which are or may be the subject of any dispute, or which may expose Finfo to any civil or criminal proceedings in Singapore or any part of the world.</li>
		</ol>
	</li>
	<li>
		<b>Third party providers</b><br>
		The Customer acknowledges that the Services may enable or assist it to access the website content of, correspond with, and purchase products or services from, third parties via third-party websites and that it does so solely at its own risk. Finfo makes no representation, warranty or commitment and shall have no liability or obligation whatsoever in relation to the content (including information, news, advertisements, listings, pricing, data, text, audio, video, pictures, graphics, software and other content) or use of, or correspondence with, any such third-party website, or any transactions completed, and any contract entered into by the Customer, with any such third party. Any contract entered into and any transaction completed via any third-party website is between the Customer and the relevant third party, and not Finfo. Finfo recommends that the Customer refers to the third party&#39;s website terms and conditions and privacy policy prior to using the relevant third-party website. Finfo does not endorse or approve any third-party website nor the content of any of the third-party website made available
	</li>
	<li>
		<b>Charges and payment</b>
		<ol>
			<li>The Customer shall pay the Subscription Fees to Finfo for the Subscription in accordance with this clause 6 and Schedule 1.</li>
			<li>The Customer shall on the Effective Date provide to Finfo valid, up-to- date and complete credit card or direct debit details, and any other contact or billing details as required by Finfo, and hereby authorises Finfo to bill the Customer:
				<ol class="lower-latin-listing">
					<li>on the Effective Date for the Subscription Fees payable in respect of the Initial Subscription Term; and</li>
					<li>subject to clause 11.1, for the Subscription Fees payable in respect of the next Renewal Period, prior to the commencement of the next Renewal Period.</li>
				</ol>
			</li>
			<li>If Finfo has not received payment before the commencement of the Initial Subscription Term or any Renewal Period, then without prejudice to any other rights and remedies of Finfo, Finfo may disable the Customer&#39;s password, Account and access to all or part of the Services and Finfo shall be under no obligation to provide any or all of the Services while the Subscription Fees concerned remain unpaid.</li>
			<li>All amounts and fees stated or referred to in this Agreement:
				<ol class="lower-latin-listing">
					<li>shall be payable in Singapore Dollars;</li>
					<li>are, subject to clause 10.2(b), non-cancellable and non-refundable; and</li>
					<li>are exclusive of Goods and Services Tax, which shall be added to Finfo&#39;s invoice(s) at the appropriate rate.</li>
				</ol>
			</li>
			<li>Finfo shall be entitled to increase the Subscription Fees from time to time and Schedule 1 shall be deemed to have been amended accordingly.</li>
		</ol>
	</li>
	<li>
		<b>Proprietary rights</b>
		<ol>
			<li>The Customer acknowledges and agrees that Finfo and/or its licensors own all intellectual property rights in the Site, Services, Software, Content and the Documentation. Except as expressly stated herein, this Agreement does not grant the Customer any rights to, under or in, any patents, copyright, database right, trade secrets, trade names, trade marks (whether registered or unregistered), or any other rights or licences (<b>Intellectual Property Rights</b>) in respect of the Site, Services, Software, Content or the Documentation.</li>
			<li>If at any time through access and/or use of the Site, Services, Software, Content and Documentation the Customer or any of its Authorised Users come to own any intellectual property rights other than in accordance with clause 7.1, the Customer shall (and shall procure that its Authorised Users shall) on request from Finfo and/or its licensors at its own expense:
				<ol class="lower-latin-listing">
					<li>Assign such Intellectual Property Rights to Finfo and/or its licensors; and</li>
					<li>To the extent permitted by law, waive all moral rights (and analogous rights worldwide in connection with such Intellectual Property Rights.</li>
				</ol>
				At Finfo’s request and at the Customer’s expense, if the Customer is to assign or procure the assignment of Intellectual Property Rights in accordance with clause 7.2, the Customer shall promptly execute deeds and other documents (including registration documents) and do such things as are necessary to confirm the ownership of Intellectual Property Rights assigned pursuant to clause 7.2.
			</li>
			<li>This Agreement shall not prevent Finfo from entering into similar agreements with third parties, or from independently developing, using, selling or licensing documentation, products and/or services which are similar to those provided under this Agreement.</li>
			<li>The intellectual Property Rights in all Customer Data and Materials shall be retained by the Customer, subject to the licences granted by the Customer under this Agreement (including under clause 4.4), and subject to Finfo’s rights in the compilation of all Customer Data and Materials.</li>
		</ol>
	</li>
	<li>
		<b>Confidentiality</b>
		<ol>
			<li>Each party may be given access to Confidential Information from the other party in order to perform its obligations under this Agreement. A party&#39;s Confidential Information shall not be deemed to include information that:
				<ol class="lower-latin-listing">
					<li>is or becomes publicly known other than through any act or omission of the receiving party;</li>
					<li>was in the other party&#39;s lawful possession before the disclosure;</li>
					<li>is lawfully disclosed to the receiving party by a third party without restriction on disclosure; or</li>
					<li>is independently developed by the receiving party, which independent development can be shown by written evidence.</li>
				</ol>
			</li>
			<li>Subject to clause 8.3, each party shall hold the other&#39;s Confidential Information in confidence and not use or make the other&#39;s Confidential Information available to any third party for any purpose other than the implementation of this Agreement.</li>
			<li>Finfo may disclose the Customer’s Confidential Information to the extent such Confidential Information is required to be disclosed by law, by any governmental or other regulatory authority or by a court or other authority of competent jurisdiction.</li>
			<li>Finfo shall not be responsible for any loss, destruction, alteration or disclosure of the Customer’s Confidential Information caused by any third party.</li>
			<li>The above provisions of this clause 8 shall survive termination of this Agreement.</li>
		</ol>
	</li>
	<li>
		<b>Indemnity</b>
		<ol>
			<li>The Customer shall indemnify and hold Finfo, its subsidiaries, affiliates, officers, agents and employees, harmless from all claims, demands, actions, proceedings, liabilities (including statutory liability and liability to third parties), penalties, and costs (including without limitation, legal costs on a full indemnity basis), awards, losses and/or expenses arising out of or in connection with the Customer&#39;s use of the Site, Services, Software, Content and/or Documentation, including:
				<ol class="lower-latin-listing">
					<li>the Customer’s submission of any Improper Works; and</li>
					<li>any claims by the Customer’s investors that Materials or other information on the Microsite are inaccurate or misleading.</li>
				</ol>
			</li>
			<li>In no event shall Finfo, its subsidiaries, affiliates, officers, agents or employees be liable to the Customer to the extent that any losses are caused by:
				<ol class="lower-latin-listing">
					<li>a modification of the Site, Services, Software, Content or Documentation by anyone other than Finfo; or</li>
					<li>the Customer&#39;s use of the Site, Services, Software, Content or Documentation in a manner contrary to the instructions given to the Customer by Finfo; or</li>
					<li>the Customer&#39;s use of the Site, Services, Software, Content or Documentation after notice of an alleged or actual infringement from Finfo or any appropriate authority.</li>
				</ol>
			</li>
		</ol>
	</li>
	<li>
		<b>Limitation of liability</b>
		<ol>
			<li>Except as expressly and specifically provided in this Agreement, unless prohibited by applicable law:
				<ol class="lower-latin-listing">
					<li>the Customer assumes sole responsibility for results obtained from the use of the Site, Services, Software, Content and the Documentation by the Customer, and for conclusions drawn from such use. Finfo shall have no liability for any damage caused by errors or omissions in any information, instructions or scripts provided to Finfo by the Customer in connection with the Services, or any actions taken by Finfo at the Customer&#39;s direction;</li>
					<li>Finfo does not warrant that the Customer&#39;s use of the Services will be uninterrupted or error-free, or that the Site, Services, Software, Content, Documentation and/or the information obtained by the Customer through the Services will meet the Customer&#39;s requirements;</li>
					<li>Finfo is not responsible for any delays, delivery failures, or any other loss or damage resulting from the transfer of data over communications networks and facilities, including the internet, and the Customer acknowledges that the Site, Services, Software, Content and Documentation may be subject to limitations, delays and other problems inherent in the use of such communications facilities; <br>and</li>
					<li>the Site, Services, Software, Content and the Documentation are provided to the Customer on an &quot;as is&quot;, “as available” basis, and all warranties, representations, conditions and all other terms of any kind whatsoever implied by statute or common law are, to the fullest extent permitted by applicable law, excluded from this Agreement.</li>
				</ol>
			</li>
			<li>Subject to clause 10.1, to the extent permitted by applicable law:
				<ol class="lower-latin-listing">
					<li>Finfo shall not be liable whether in tort (including negligence or breach of statutory duty), contract, misrepresentation, restitution or otherwise for any loss of profits, loss of business, depletion of goodwill and/or similar losses or loss or corruption of data or information, or pure economic loss, or for any special, indirect or consequential loss, costs, damages, charges or expenses however arising under this Agreement; and</li>
					<li>Finfo&#39;s total aggregate liability in contract, tort (including negligence or breach of statutory duty), misrepresentation, restitution or otherwise, arising in connection with the performance or contemplated performance of this Agreement shall be limited to the total Subscription Fees paid for the Subscription during the month immediately preceding the date on which the claim arose (pro-rated where the Subscription Fees are paid on an annual basis).</li>
				</ol>
			</li>
		</ol>
	</li>
	<li>
		<b>Term and termination</b>
		<ol>
			<li>This Agreement shall, unless otherwise terminated as provided in this clause 11, commence on the Effective Date and shall continue for the Initial Subscription Term and, thereafter, this Agreement shall be automatically renewed for successive periods of the duration stated in Schedule 1 (each a <b>Renewal Period</b>), unless:
				<ol class="lower-latin-listing">
					<li>the Customer notifies Finfo of termination, in writing, at least 30 days before the end of the Initial Subscription Term or any Renewal Period, in which case this Agreement shall terminate upon the expiry of the applicable Initial Subscription Term or Renewal Period; or</li>
					<li>otherwise terminated in accordance with the provisions of this Agreement;</li>
				</ol>
				and the Initial Subscription Term together with any subsequent Renewal Periods shall constitute the Subscription Term.
			</li>
			<li>Without affecting any other right or remedy available to it, Finfo may in its sole and absolute discretion and without notice:
				<ol class="lower-latin-listing">
					<li>terminate this Agreement;</li>
					<li>restrict, suspend, or terminate the Customer’s access to all or any part of the Services; and/or</li>
					<li>terminate, deactivate, suspend or delete the Customer’s Account,</li>
				</ol>
				without assigning any reason. Without prejudice to the generality of the above, Finfo reserves the right to terminate this Agreement, deactivate and terminate the Customer’s Account and/or its access to all or any part of the Services if it has been inactive for a period of thirty (30) days or more, or if the Customer is in breach of this Agreement, or if Finfo believes that the Customer has been using its Account for unlawful and/or undesirable activities.
			</li>
			<li>The Customer may terminate this Agreement with immediate effect by giving written notice to Finfo if Finfo commits a material breach of any term of this Agreement which breach is irremediable or (if such breach is remediable) fails to remedy that breach within a period of 14 days after being notified in writing to do so.</li>
			<li>On termination of this Agreement for any reason:
				<ol class="lower-latin-listing">
					<li>all licences granted under this Agreement shall immediately terminate and the Customer shall immediately cease all use of the Site, Services, Software, Content and/or the Documentation;</li>
					<li>Finfo may destroy or otherwise dispose of any Customer Data and Materials in its possession;</li>
					<li>the Customer shall not be entitled to a refund of any Subscription Fees paid before the date of termination; and</li>
					<li>any rights, remedies, obligations or liabilities of the parties that have accrued up to the date of termination, including the right to claim damages in respect of any breach of this Agreement which existed at or before the date of termination shall not be affected or prejudiced.</li>
				</ol>
			</li>
		</ol>
	</li>
	<li>
		<b>Force majeure</b><br>
		Finfo shall have no liability to the Customer under this Agreement if it is prevented from or delayed in performing its obligations under this Agreement, or from carrying on its business, by acts, events, omissions or accidents beyond its reasonable control, including, without limitation, strikes, lock-outs or other industrial disputes (whether involving the workforce of Finfo or any other party), failure of a utility service or transport or telecommunications network, act of God, war, riot, civil commotion, malicious damage, compliance with any law or governmental order, rule, regulation or direction, accident, breakdown of plant or machinery, fire, flood, storm, default of suppliers or sub-contractors, or disruption or unavailability of any Linked Sites or third party services.
	</li>
	<li>
		<b>Conflict</b>
		<ol>
			<li>If there is an inconsistency between any of the provisions in the main body of this Agreement and the Schedules (including the Web Terms), the provisions in the main body of this Agreement shall prevail.</li>
		</ol>
	</li>
	<li>
		<b>Variation</b>
			<ol>
				<li>No variation of this Agreement shall be effective unless it is in writing and signed by the parties (or their authorised representatives).</li>
			</ol>
	</li>
	<li>
		<b>Waiver</b>
			<ol>
				<li>No failure or delay by a party to exercise any right or remedy provided under this Agreement or by law shall constitute a waiver of that or any other right or remedy, nor shall it prevent or restrict the further exercise of that or any other right or remedy. No single or partial exercise of such right or remedy shall prevent or restrict the further exercise of that or any other right or remedy.</li>
			</ol>
	</li>
	<li>
		<b>Severability</b>
			<ol>
				<li>If any provision (or part of a provision) of this Agreement is found by any court or administrative body of competent jurisdiction to be invalid, unenforceable or illegal, the other provisions shall remain in force.</li>
				<li>If any invalid, unenforceable or illegal provision would be valid, enforceable or legal if some part of it were deleted, the provision shall apply with whatever modification is necessary to give effect to the commercial intention of the parties.</li>
			</ol>
	</li>
	<li>
		<b>Entire agreement</b>
		<ol>
			<li>This Agreement constitutes the entire agreement between the parties and supersedes and extinguishes all previous agreements, promises, assurances, warranties, representations and understandings between them, whether written or oral, relating to its subject matter.</li>
		</ol>
	</li>
	<li>
		<b>Assignment</b>
		<ol>
			<li>The Customer shall not, without the prior written consent of Finfo, assign, transfer, charge, sub-contract or deal in any other manner with all or any of its rights or obligations under this Agreement.</li>
			<li>Finfo may at any time assign, transfer, charge, sub-contract or deal in any other manner with all or any of its rights or obligations under this Agreement.</li>
		</ol>
	</li>
	<li>
		<b>No partnership or agency</b>
		<ol>
			<li>Nothing in this Agreement shall constitute or be deemed to constitute an agency, partnership or joint venture between the parties and neither party shall have any authority to bind the other in any way.</li>
		</ol>
	</li>
	<li>
		<b>Third party rights</b>
		<ol>
			<li>Except as provided for in clause 9, a person or entity who is not a party to this Agreement shall have no right under the Contracts (Rights of Third Parties) Act (Cap. 53B) to enforce any term of this Agreement, regardless of whether such person or entity has been identified by name, as a member of a class or as answering a particular description</li>
		</ol>
	</li>
	<li>
		<b>Counterparts</b>
		<ol>
			<li>This Agreement may be executed in any number of counterparts each of which when executed shall constitute a duplicate original, but all counterparts shall together constitute one agreement.</li>
		</ol>
	</li>
	<li>
		<b>Governing law &amp; jurisdiction</b>
		<ol>
			<li>This Agreement and all matters relating to the Customer’s access to or use of the Services shall be governed by and construed in accordance with the laws of Singapore including without limitation the provisions of the Singapore Evidence Act (Chapter 97) and the Electronic Transactions Act (Cap. 88), without giving effect to any principles of conflicts of law.</li>
			<li>Any dispute arising out of or in connection with this Agreement, including any question regarding its existence, validity or termination, shall be referred to and finally resolved by arbitration administered by the Singapore International Arbitration Centre in accordance with the Arbitration Rules of the Singapore International Arbitration Centre for the time being in force, which rules are deemed to be incorporated by reference in this clause. The seat of the arbitration shall be Singapore, the tribunal shall consist of one arbitrator, and the language of the arbitration shall be English.</li>
		</ol>
	</li>

</ol>
</div>
        </div></div>            </div>
        </div>
    </div>
    
            <script>var
$=jQuery;var
$rowCustom_599be6367513c=$('#rowCustom-599be6367513c');if("1"){$rowCustom_599be6367513c.find('> .wrap').addClass('box_size_container');$rowCustom_599be6367513c.find('> .wrap').addClass('box_size_container');}else{$rowCustom_599be6367513c.find('> .wrap').removeClass('box_size_container');$rowCustom_599be6367513c.find('> .wrap').removeClass('box_size_container');}$('#rowCustom-599be6367513c').find('.row-videobg').not('.row-videobg[data-row-id="rowCustom-599be6367513c"]').remove();</script>

        </div> <!-- End wrap -->

        <style>    #rowCustom-599be6367513c{  margin-top:0px;margin-bottom:0px;} #rowCustom-599be6367513c{  padding-top:100px;padding-bottom:45px;padding-right:0px;padding-left:0px;} .sectionOverlay.box_size{ width:65%} .sectionOverlay .box_size_container{ width:65%} #rowCustom-599be6367513c:after{  background-color:rgba(255,255,255,1)}   </style>
    </div> <!-- End main row -->

@stop