<?

	$keys_klout = array(
			"9zsu7psvjznavgyaxuyh4w9n", 
			"3feep67nr3hrchknd9cxdq64", 
			"xfrb568aywud7dz3uckmkuqv", 
			"745u85ay6gr8upyc7cyuywna", 
			"bwbr54bkynng8q8zk28pd3x5", 
			"n97ymy6gay3e7agh65m3fhzg", 
			"jc5m6hn3dnvg4bjh9vuvghtd", 
			"534j4yn8afbsu65g78pbnqkn", 
			"9zqv2yzmtdcuxcm3pr8mdjp8", 
			"fer7h4prh96ggy33hfxvcg4g", 
			"g32zsdd5fupjjyc6q2878aj5", 
			"d6864qxn5897ba7k3r8v92x4", 
			"5qkw2uprxmf6a2jjq8cwg7d3", 
			"dsrhzsykztrhz7hmbg9kpddd", 
			"rah3me7b9hk7k43wjgq7fywp", 
			"u6du34mp4bezhht3gfkwkgd9", 
			"997dx2njchwpcptcc9a7mczm", 
			"w2uyfrthtgmqu4f25gs8vav9"
		);

	$key_klout_id = $keys_klout[rand(0,17)];
	$key_klout_score = $keys_klout[rand(0,17)];
	
	$app_kred = array( 
				"cdc4b6bb",
				"db282bee",
				"608d0fc5",
				"c3d3eea9"
		);
	
$keys_kred = array(
                                "db282bee" => array(
                                        "00b4aaea20eca3423b93998b1b8628e9",
                                        "133e3f90fb405643a1e7da1394ddbee8",
                                        "221f1108f433d42574de555c5d5c6520",
                                        "a3f272151bab43d0d2f72557005a9199",
                                        "b848ce3610fa0e86ab7e59c3bf03a526"
                                        ),
                                "cdc4b6bb" => array(
                                        "960802deea6d048affdc447c6e38375d",
                                        "53a7f9b87500826e6a0e21d8a6229d70",
                                        "b00ed5ba7eaaaef213dbfd6d195ecab6",
                                        "c04e49107b53be12411320f44c0fd74d",
                                        "e33e9d853ba7ef9091eb7a8687767c9d"
                                ),
                                "608d0fc5" => array(
                                        "0827ddce920bcd9e1a390a687a73d39e",
                                        "fabd64d6468146a851c9b740cb7a9b6c",
                                        "4cb18e8435eb85ebc55027894f32d900",
                                        "43469a45ad7ac7fcd412d53736bab9bc",
                                        "f451722ec1e35eb910d1077c18edff4d"
                                ),
                                "c3d3eea9" => array(
                                        "14cf60264322b150a18d288ba7d4176e",
                                        "67d0f67566481f202c7bae1829ac9555",
                                        "c1020674b9cae23e78b4619ff79e4e82",
                                        "8c94a2a74d441de71c30be818423207b",
                                        "5f4e5bb83783c926a6cc74b20f0492ab"
                                )
                        );

$key_kred_app = $app_kred[rand(0,3)];

$key_kred_id = $keys_kred[$key_kred_app][rand(0,4)];
	
?>
