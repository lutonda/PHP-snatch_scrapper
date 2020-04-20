<?php


require __DIR__ . '/vendor/autoload.php'; // Composer's autoloader

$date=date("Y-m-dTH:i:s");

$sources = [
    ['BFA', 'https://www.bfa.ao/pt/particulares/pesquisa-de-cambios/', 'table.table.table-style-1.js-exchangeTable','table.table.table-style-1.js-exchangeTable tbody tr:not(.highlight)'],
    ['AGT', 'https://agt.minfin.gov.ao/PortalAGT/#!/', 'cambio table.table.table-condensed.table-blocked', 'cambio table.table.table-condensed.table-blocked'],
    ['Atlantico', 'https://www.atlantico.ao/pt/particulares/Pages/home.aspx', '.carousel.carousel-movefour.slide .carousel-inner', '.carousel.carousel-movefour.slide .carousel-inner'],
    ['SOL', 'https://www.bancosol.ao/', 'table#CTRL_Cambios1_dynControl_quotationGrid', 'table#CTRL_Cambios1_dynControl_quotationGrid'],
    ['BNI', 'http://www.bni.ao/pt/taxas/', '.col-xs-12.rates-table table.table', '.col-xs-12.rates-table table.table'],
    ['BCI', 'https://www.bci.ao/', '.tabelas-container div.tabelas div.body', '.tabelas-container div.tabelas div.body'],
    ['BPC', 'http://www.bpc.ao/bpc/pt/', '#foottoolbox .rightquarter .cambio tbody', '#foottoolbox .rightquarter .cambio tbody']
];
function getContent($w,$x,$y,$z,$date){

    $client = \Symfony\Component\Panther\Client::createChromeClient();
    // Or, if you care about the open web and prefer to use Firefox
    
    //$client = \Symfony\Component\Panther\Client::createFirefoxClient();

    $client->request('GET', $y); // Yes, this website is 100% written in JavaScript
    //var_dump($client->text());
    //$client->clickLink('Support');

    // Wait for an element to be rendered
    $crawler = $client->waitFor($w);


    $data=$crawler->filter($z)->text();

    echo($data);
    echo '---------------------------------------------';
    echo '---------------------------------------------';
    echo '---------------------------------------------';
    $url='?';
    $url.='source='.$x;
    $url.='&data=';
    $url.= base64_encode($data);
    $url.='&date='.$date;
    echo $url;
    //$client->takeScreenshot('screen.png'); // Yeah, screenshot!

   // submitData($url);


}


function submitData($url)
{
    $base_srv='http://0.0.0.0:8800';
    $base_url='/sync/_io/download/local';
    echo '-------------------------------------------------------------------';
    echo 'sending data';
    echo $base_srv.$base_url.$url;

    $ch = curl_init($base_srv.$base_url.$url);
    
    //curl_setopt($ch, CURLOPT_FILE);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
}

foreach ($sources as $s) {
    getContent($s[3],$s[0],$s[1],$s[2],$date);
}