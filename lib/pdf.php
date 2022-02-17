<?php
if( !class_exists( 'FPDF' ) )
    include( 'libs/fpdf/fpdf.php');

function cbaffmach_output_pdf( $post_id ) {
$pdf = new CBAUTOPDF();
    $post = get_post( $post_id );
    $title = get_the_title( $post_id );
    if( function_exists( 'iconv') )
        $title = iconv('UTF-8', 'windows-1252', html_entity_decode($title));
    else
        $title = utf8_decode( $title );

    // $thumbnail = cbaffmach_get_thumbnail( $post_id );
    $title_line_height = 10;
    $content_line_height = 8;

    $pdf->AddPage();
    $pdf->SetFont( 'Arial', '', 42 );
    // $pdf->Write(20, $title);
    $pdf->setY( 15 );
    // $pdf->WriteHTML( '<h1 align="center"><center>'.$title.'</center></h1>' );
    $line_height = 5;
    $width = 189;
    $height = (ceil(($pdf->GetStringWidth($title) / $width)) * $line_height);
    // $pdf->MultiCell(0,0,$title,0,'C');
    $pdf->Multicell($width,$height,$title,0,'C');
    $pdf->Ln(15);

    // Image
    $page_width = $pdf->GetPageWidth() - 20;
    $max_image_width = $page_width;

    $image = get_the_post_thumbnail_url( $post->ID );
    if( ! empty( $image ) ) {
        $pdf->Image( $image, 50, 120, 100 );
        // $pdf->imageCenterCell( $image,10,10,40,50);
    }
    $pdf->SetFont( 'Arial', '', 18 );

    $title2 = '<p><center>More info at <a href="'.get_permalink( $post_id ).'">'.get_permalink( $post_id ).'</a></center></p>';
    $pdf->setY( 240 );
    $pdf->WriteHTML( $title2 );
    // $pdf->Cell(0,150,$title2,0,0,'C');

        $pdf->AddPage();
        $pdf->SetFont( 'Arial', '', 22 );
        $pdf->Write($title_line_height, $title);

        // Add a line break

        
        // Post Content
        $pdf->Ln(10);
        $pdf->SetFont( 'Arial', '', 12 );
        $content = strip_tags( cbaffmach_get_content_by_id( $post_id ), '<p><br><ul><li><ol><b><strong><u><a><span><h1><h2><h3>' );
        $importing_settings =  cbaffmach_get_import_settings( );
        $content = cbaffmach_clickbank_replace( $content, $importing_settings );
        // $content = utf8_encode( html_entity_decode( $content ) );
        if( function_exists( 'iconv') )
            $content = iconv('UTF-8', 'windows-1252//IGNORE', html_entity_decode( $content ) );
        else
            $content = utf8_decode( html_entity_decode( $content ) );

        $pdf->WriteHTML( $content );

    $pdf->Output('D','post_'.$post_id.'.pdf');
    exit;
}

/**
 * Modified from http://www.fpdf.org/en/script/script42.php
 */

//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function cbaffmach_hex2dec($color = "#000000"){
    $R = substr($color, 1, 2);
    $rouge = hexdec($R);
    $V = substr($color, 3, 2);
    $vert = hexdec($V);
    $B = substr($color, 5, 2);
    $bleu = hexdec($B);
    $tbl_color = array();
    $tbl_color['R']=$red;
    $tbl_color['G']=$green;
    $tbl_color['B']=$blue;
    return $tbl_color;
}

//conversion pixel -> millimeter at 72 dpi
function cbaffmach_px2mm($px){
    return $px*25.4/72;
}

function cbaffmach_txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}
////////////////////////////////////
class CBAUTOPDF extends FPDF
{
//variables of html parser
protected $B;
protected $I;
protected $U;
protected $HREF;
protected $fontList;
protected $issetfont;
protected $issetcolor;

function __construct($orientation='P', $unit='mm', $format='A4')
{
    //Call parent constructor
    parent::__construct($orientation,$unit,$format);
    //Initialization
    $this->B=0;
    $this->I=0;
    $this->U=0;
    $this->HREF='';
    $this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
    $this->issetfont=false;
    $this->issetcolor=false;
}

function imageCenterCell($file, $x, $y, $w, $h)
    {
        if (!file_exists($file)) 
        {
            $this->Error('File does not exist: '.$file);
        }
        else
        {
            list($width, $height) = getimagesize($file);
            $ratio=$width/$height;
            $zoneRatio=$w/$h;
            // Same Ratio, put the image in the cell
            if ($ratio==$zoneRatio)
            {
                $this->Image($file, $x, $y, $w, $h);
            }
            // Image is vertical and cell is horizontal
            if ($ratio<$zoneRatio)
            {
                $neww=$h*$ratio; 
                $newx=$x+(($w-$neww)/2);
                $this->Image($file, $newx, $y, $neww);
            }
            // Image is horizontal and cell is vertical
            if ($ratio>$zoneRatio)
            {
                $newh=$w/$ratio; 
                $newy=$y+(($h-$newh)/2);
                $this->Image($file, $x, $newy, $w);
            }
        }
    }

function WriteHTML($html)
{
    //HTML parser
    $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote><ol><ul><li><h1><h2><h3><center>");
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if($this->HREF)
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,stripslashes(cbaffmach_txtentities($e)));
        }
        else
        {
            //Tag
            if($e[0]=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract attributes
                $a2=explode(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $attr=array();
                foreach($a2 as $v)
                {
                    if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                        $attr[strtoupper($a3[1])]=$a3[2];
                }
                $this->OpenTag($tag,$attr);
            }
        }
    }
}

function OpenTag($tag, $attr)
{
    //Opening tag
    switch($tag){
        case 'STRONG':
            $this->SetStyle('B',true);
            break;
        case 'EM':
            $this->SetStyle('I',true);
            break;
        case 'B':
        case 'I':
        case 'U':
            $this->SetStyle($tag,true);
            break;
        case 'A':
            $this->HREF=$attr['HREF'];
            break;
        case 'IMG':
            if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                if(!isset($attr['WIDTH']))
                    $attr['WIDTH'] = 0;
                if(!isset($attr['HEIGHT']))
                    $attr['HEIGHT'] = 0;
                $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), cbaffmach_px2mm($attr['WIDTH']), cbaffmach_px2mm($attr['HEIGHT']));
            }
            break;
        case 'TR':
        case 'BLOCKQUOTE':
        case 'BR':
            $this->Ln(5);
            break;
        case 'H1':
        case 'H2':
        case 'H3':
            $this->Ln(15);
            if( isset( $attr['ALIGN']) )
                $this->ALIGN= $attr['ALIGN'];
            break;
        case 'LI':
            $this->Ln(8);
            break;
        case 'P':
            $this->Ln(10);
            break;
        case 'FONT':
            if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                $colour=cbaffmach_hex2dec($attr['COLOR']);
                $this->SetTextColor($colour['R'],$colour['G'],$colour['B']);
                $this->issetcolor=true;
            }
            if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                $this->SetFont(strtolower($attr['FACE']));
                $this->issetfont=true;
            }
            break;
    }
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='STRONG')
        $tag='B';
    if($tag=='EM')
        $tag='I';
    if($tag=='B' || $tag=='I' || $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
    if($tag=='FONT'){
        if ($this->issetcolor==true) {
            $this->SetTextColor(0);
        }
        if ($this->issetfont) {
            $this->SetFont('arial');
            $this->issetfont=false;
        }
    }
}

function SetStyle($tag, $enable)
{
    //Modify style and select corresponding font
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
    {
        if($this->$s>0)
            $style.=$s;
    }
    $this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}

}//end of class


?>