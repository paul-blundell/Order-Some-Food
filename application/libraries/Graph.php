<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Graph Libary
 *
 * @author Junaid Shabbir
 * @tutorial http://www.qualitycodes.com/tutorial.php?articleid=20&title=How-to-create-bar-graph-in-PHP-with-dynamic-scaling
 */
class Graph {
    
        private $values;
        private $location;
        
        private $width;
        private $height;
        private $margin;
        private $barwidth;
        
        private $img;
        
        public function make($values, $location, $width = 450, $height = 300, $margin = 20, $barwidth = 20)
        {
                $this->values = $values;
                $this->location = $location;
                $this->width = $width;
                $this->height = $height;
                $this->margin = $margin;
                $this->barwidth = $barwidth;
                
                $this->generate();
                $this->save();
        }
        
        private function generate()
        {
                $graph_width = $this->width - $this->margin * 2;
                $graph_height = $this->height - $this->margin * 2; 
                $img = imagecreate($this->width, $this->height);
         
                $total_bars = count($this->values);
                $gap = ($graph_width - $total_bars * $this->barwidth) / ($total_bars + 1);
        
                // Colours of Graph
                $bar_color = imagecolorallocate($img,0,64,128);
                $background_color = imagecolorallocate($img,240,240,255);
                $border_color = imagecolorallocate($img,255,255,255);
                $line_color = imagecolorallocate($img,220,220,220);
         
                // Border around the Graph
                imagefilledrectangle($img,1,1,$this->width-2,$this->height-2,$border_color);
                imagefilledrectangle($img,$this->margin,$this->margin,$this->width-1-$this->margin,$this->height-1-$this->margin,$background_color);
        
         
                // Adjust the scale by getting the max value
                $max_value = max($this->values);
                $ratio = $graph_height / $max_value;
        
         
                // Create scale and lines
                $horizontal_lines = 20;
                $horizontal_gap = $graph_height / $horizontal_lines;
        
                for($i=1;$i<=$horizontal_lines;$i++){
                        $y=$this->height - $this->margin - $horizontal_gap * $i ;
                        imageline($img,$this->margin,$y,$this->width-$this->margin,$y,$line_color);
                        $v=intval($horizontal_gap * $i /$ratio);
                        imagestring($img,0,5,$y-5,$v,$bar_color);
        
                }
         
         
                // Draw the bars
                for($i=0;$i< $total_bars; $i++){ 
                        list($key,$value)=each($this->values); 
                        $x1 = $this->margin + $gap + $i * ($gap+$this->barwidth) ;
                        $x2 = $x1 + $this->barwidth; 
                        $y1 = $this->margin + $graph_height - intval($value * $ratio) ;
                        $y2 = $this->height-$this->margin;
                        imagestring($img,0,$x1+3,$y1-10,$value,$bar_color);
                        imagestring($img,0,$x1+3,$this->height-15,$key,$bar_color);		
                        imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
                }
                
                $this->img = $img;
        }
        
        private function save()
        {
                imagepng($this->img, $this->location);
        }
}