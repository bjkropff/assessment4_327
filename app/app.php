<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Brand.php";
    require_once __DIR__."/../src/Store.php";
    $app = new Silex\Application();

    $app['debug'] = true;

    $DB = new PDO('pgsql:host=localhost;dbname=shoes;user=brian;password=1234');

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //user to the home page
    $app->get("/", function() use ($app) {
        return $app['twig']->render('home.html.twig');
    });

    //READ ALLS STORES
    //user to the stores page
    $app->get('/stores', function() use ($app){
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //DELETE ALL stores
    //user to the stores page:
    $app->delete("/delete_all_stores", function() use ($app) {
        Store::deleteAll();
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //CREATE STORE
    //add from the stores page to the list of stores
    $app->post("/stores", function() use ($app){
        $name = $_POST['name'];
        $new_store = new Store($name);
        $new_store->save();
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //READ, single
    //user to the named store page
    $app->get("/stores/{id}", function($id) use ($app) {
        $store = Store::find($id);
        return $app['twig']->render('name.html.twig', array('store' => $store, 'brands' => $store->getBrands(), 'store_brands' => Brand:: getAll()));
    });

    //READ on edit page
    //user to edit page (a single store) by id
    $app->get("/stores/{id}/edit", function($id) use ($app){
        $store = Store::find($id);
        return $app['twig']->render('name_edit.html.twig', array('store' => $store, 'brands' => $store->getBrands(), 'store_brands' => Brand::getAll()));
    });

    //UPDATE, name
    //edit store name
    $app->patch('/stores/{id}', function($id) use ($app){
        $new_name = $_POST['new_name'];
        $store = Store::find($id);
        $store->update($new_name);//Not working
        return $app['twig']->render('name.html.twig', array('store' => $store, 'brands' => $store->getBrands(), 'store_brands' => Brand::getAll()));
    });


    //DELETE STORE, single
    //delete store on store's page and return user to the stores page
    $app->delete("/stores/{id}/delete", function($id) use ($app) {
        $store = Store::find($id);
        $store->delete();
        return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //ADD BRAND
    //add brand to the store:
    $app->post('/stores/{id}', function($id) use ($app){
        $brand = Brand::find($_POST['brand_id']);
        $store = Store::find($id);
        $store->addBrand($brand);
        return $app['twig']->render('name_edit.html.twig', array('store' => $store, 'brands' => $store->getBrands(), 'store_brands' => Brand::getAll()));
    });

    //DELETE BRAND
    //delete a brand from a store
    $app->delete("/store/{id}/brand", function($id) use ($app) {
        $store = Store::find($_POST['store_id']);
        $brand = Brand::find($_POST['brand_id']);
        $store->deleteBrand($brand);;
        return $app['twig']->render('name.html.twig', array('store' => $store, 'brands' => $store->getBrands(), 'store_brands' => Brand::getAll()));
    });

    // $app->post("/stores/{id}/edit", function() use ($app) {
    //     $store = Store::find($id);
    //     return $app['twig']->render('name_edit.html.twig', array('store' => $store, 'brands' => $store->getBrands(), 'store_brands' => Brand::getAll()));
    // });

    //Brands
    //READ ALL
    //user to brands page
    $app->get('/brands', function() use ($app){
        return $app['twig']->render('brands.html.twig', array('brands' => Brand::getAll()));
    });

    //CREATE Brand
    //add from brands page to the list of brands
    $app->post('/brands', function () use ($app){
        $new_brand = new Brand($_POST['style']);
        $new_brand->save();
        return $app['twig']->render('brands.html.twig', array('brands' => Brand::getAll(), 'every_store' => Store::getAll()));
    });

    //DELETE ALL Brands
    //user to the brands page
    $app->delete("/delete_all_brands", function() use ($app) {
        Brand::deleteAll();
        return $app['twig']->render('brands.html.twig', array('brands' => Store::getAll()));
    });

    //READ, single brand, style
    //user to the styles page:
    $app->get("/brands/{id}", function($id) use ($app){
        $brand = Brand::find($id);
        return $app['twig']->render('style.html.twig', array('brand' => $brand, 'stores' => $brand->getStores(), 'every_store' => Store::getAll()));
    });

    //READ on edit page
    //user to edit page (a single brand) by id:
    $app->get("/brands/{id}/edit", function($id) use ($app){
        $brand = Brand::find($id);
        return $app['twig']->render('style_edit.html.twig', array('brand' => $brand, 'stores' => $brand->getStores(), 'every_store' => Store::getAll()));
    });

    return $app
?>
