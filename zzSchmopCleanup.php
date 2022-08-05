<?php

include 'Libraries/SHMOPLibraries.php';

for ($i = 0; $i < 10000; ++$i) {
  DeleteCache($i);
}
