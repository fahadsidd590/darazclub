<?php
/**
 * Interface QRDataInterface
 *
 * @filesource   QRDataInterface.php
 * @created      01.12.2015
 * @package      chillerlan\QRCode\Data
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace chillerlan\QRCode\Data;

/**
 *
 */
interface QRDataInterface {

	/**
	 * @link http://www.qrcode.com/en/about/version.html
	 */
	const MAX_LENGTH =array(
	null, // start at 1
	//  [NUMERIC => [L, M, Q, H ], ALPHANUM => [L, M, Q, H], BINARY => [L, M, Q, H  ], KANJI => [L, M, Q, H   ]]  // modules
		array( array( 41, 34, 27, 17 ), array( 25, 20, 16, 10 ), array( 17, 14, 11, 7 ), array( 10, 8, 7, 4 ) ), //  21
		array( array( 77, 63, 48, 34 ), array( 47, 38, 29, 20 ), array( 32, 26, 20, 14 ), array( 20, 16, 12, 8 ) ), //  25
		array( array( 127, 101, 77, 58 ), array( 77, 61, 47, 35 ), array( 53, 42, 32, 24 ), array( 32, 26, 20, 15 ) ), //  29
		array( array( 187, 149, 111, 82 ), array( 114, 90, 67, 50 ), array( 78, 62, 46, 34 ), array( 48, 38, 28, 21 ) ), //  33
		array( array( 255, 202, 144, 106 ), array( 154, 122, 87, 64 ), array( 106, 84, 60, 44 ), array( 65, 52, 37, 27 ) ), //  37
		array( array( 322, 255, 178, 139 ), array( 195, 154, 108, 84 ), array( 134, 106, 74, 58 ), array( 82, 65, 45, 36 ) ), //  41
		array( array( 370, 293, 207, 154 ), array( 224, 178, 125, 93 ), array( 154, 122, 86, 64 ), array( 95, 75, 53, 39 ) ), //  45
		array( array( 461, 365, 259, 202 ), array( 279, 221, 157, 122 ), array( 192, 152, 108, 84 ), array( 118, 93, 66, 52 ) ), //  49
		array( array( 552, 432, 312, 235 ), array( 335, 262, 189, 143 ), array( 230, 180, 130, 98 ), array( 141, 111, 80, 60 ) ), //  53
		array( array( 652, 513, 364, 288 ), array( 395, 311, 221, 174 ), array( 271, 213, 151, 119 ), array( 167, 131, 93, 74 ) ), //  57
		array( array( 772, 604, 427, 331 ), array( 468, 366, 259, 200 ), array( 321, 251, 177, 137 ), array( 198, 155, 109, 85 ) ), //  61
		array( array( 883, 691, 489, 374 ), array( 535, 419, 296, 227 ), array( 367, 287, 203, 155 ), array( 226, 177, 125, 96 ) ), //  65
		array( array( 1022, 796, 580, 427 ), array( 619, 483, 352, 259 ), array( 425, 331, 241, 177 ), array( 262, 204, 149, 109 ) ), //  69 NICE!
		array( array( 1101, 871, 621, 468 ), array( 667, 528, 376, 283 ), array( 458, 362, 258, 194 ), array( 282, 223, 159, 120 ) ), //  73
		array( array( 1250, 991, 703, 530 ), array( 758, 600, 426, 321 ), array( 520, 412, 292, 220 ), array( 320, 254, 180, 136 ) ), //  77
		array( array( 1408, 1082, 775, 602 ), array( 854, 656, 470, 365 ), array( 586, 450, 322, 250 ), array( 361, 277, 198, 154 ) ), //  81
		array( array( 1548, 1212, 876, 674 ), array( 938, 734, 531, 408 ), array( 644, 504, 364, 280 ), array( 397, 310, 224, 173 ) ), //  85
		array( array( 1725, 1346, 948, 746 ), array( 1046, 816, 574, 452 ), array( 718, 560, 394, 310 ), array( 442, 345, 243, 191 ) ), //  89
		array( array( 1903, 1500, 1063, 813 ), array( 1153, 909, 644, 493 ), array( 792, 624, 442, 338 ), array( 488, 384, 272, 208 ) ), //  93
		array( array( 2061, 1600, 1159, 919 ), array( 1249, 970, 702, 557 ), array( 858, 666, 482, 382 ), array( 528, 410, 297, 235 ) ), //  97
		array( array( 2232, 1708, 1224, 969 ), array( 1352, 1035, 742, 587 ), array( 929, 711, 509, 403 ), array( 572, 438, 314, 248 ) ), // 101
		array( array( 2409, 1872, 1358, 1056 ), array( 1460, 1134, 823, 640 ), array( 1003, 779, 565, 439 ), array( 618, 480, 348, 270 ) ), // 105
		array( array( 2620, 2059, 1468, 1108 ), array( 1588, 1248, 890, 672 ), array( 1091, 857, 611, 461 ), array( 672, 528, 376, 284 ) ), // 109
		array( array( 2812, 2188, 1588, 1228 ), array( 1704, 1326, 963, 744 ), array( 1171, 911, 661, 511 ), array( 721, 561, 407, 315 ) ), // 113
		array( array( 3057, 2395, 1718, 1286 ), array( 1853, 1451, 1041, 779 ), array( 1273, 997, 715, 535 ), array( 784, 614, 440, 330 ) ), // 117
		array( array( 3283, 2544, 1804, 1425 ), array( 1990, 1542, 1094, 864 ), array( 1367, 1059, 751, 593 ), array( 842, 652, 462, 365 ) ), // 121
		array( array( 3517, 2701, 1933, 1501 ), array( 2132, 1637, 1172, 910 ), array( 1465, 1125, 805, 625 ), array( 902, 692, 496, 385 ) ), // 125
		array( array( 3669, 2857, 2085, 1581 ), array( 2223, 1732, 1263, 958 ), array( 1528, 1190, 868, 658 ), array( 940, 732, 534, 405 ) ), // 129
		array( array( 3909, 3035, 2181, 1677 ), array( 2369, 1839, 1322, 1016 ), array( 1628, 1264, 908, 698 ), array( 1002, 778, 559, 430 ) ), // 133
		array( array( 4158, 3289, 2358, 1782 ), array( 2520, 1994, 1429, 1080 ), array( 1732, 1370, 982, 742 ), array( 1066, 843, 604, 457 ) ), // 137
		array( array( 4417, 3486, 2473, 1897 ), array( 2677, 2113, 1499, 1150 ), array( 1840, 1452, 1030, 790 ), array( 1132, 894, 634, 486 ) ), // 141
		array( array( 4686, 3693, 2670, 2022 ), array( 2840, 2238, 1618, 1226 ), array( 1952, 1538, 1112, 842 ), array( 1201, 947, 684, 518 ) ), // 145
		array( array( 4965, 3909, 2805, 2157 ), array( 3009, 2369, 1700, 1307 ), array( 2068, 1628, 1168, 898 ), array( 1273, 1002, 719, 553 ) ), // 149
		array( array( 5253, 4134, 2949, 2301 ), array( 3183, 2506, 1787, 1394 ), array( 2188, 1722, 1228, 958 ), array( 1347, 1060, 756, 590 ) ), // 153
		array( array( 5529, 4343, 3081, 2361 ), array( 3351, 2632, 1867, 1431 ), array( 2303, 1809, 1283, 983 ), array( 1417, 1113, 790, 605 ) ), // 157
		array( array( 5836, 4588, 3244, 2524 ), array( 3537, 2780, 1966, 1530 ), array( 2431, 1911, 1351, 1051 ), array( 1496, 1176, 832, 647 ) ), // 161
		array( array( 6153, 4775, 3417, 2625 ), array( 3729, 2894, 2071, 1591 ), array( 2563, 1989, 1423, 1093 ), array( 1577, 1224, 876, 673 ) ), // 165
		array( array( 6479, 5039, 3599, 2735 ), array( 3927, 3054, 2181, 1658 ), array( 2699, 2099, 1499, 1139 ), array( 1661, 1292, 923, 701 ) ), // 169
		array( array( 6743, 5313, 3791, 2927 ), array( 4087, 3220, 2298, 1774 ), array( 2809, 2213, 1579, 1219 ), array( 1729, 1362, 972, 750 ) ), // 173
		array( array( 7089, 5596, 3993, 3057 ), array( 4296, 3391, 2420, 1852 ), array( 2953, 2331, 1663, 1273 ), array( 1817, 1435, 1024, 784 ) ), // 177
	);

	const MAX_BITS = array(
	null, // start at 1
		// MAX_BITS => [L, M, Q, H ]
		array( 152, 128, 104, 72 ),
		array( 272, 224, 176, 128 ),
		array( 440, 352, 272, 208 ),
		array( 640, 512, 384, 288 ),
		array( 864, 688, 496, 368 ),
		array( 1088, 864, 608, 480 ),
		array( 1248, 992, 704, 528 ),
		array( 1552, 1232, 880, 688 ),
		array( 1856, 1456, 1056, 800 ),
		array( 2192, 1728, 1232, 976 ),
		array( 2592, 2032, 1440, 1120 ),
		array( 2960, 2320, 1648, 1264 ),
		array( 3424, 2672, 1952, 1440 ),
		array( 3688, 2920, 2088, 1576 ),
		array( 4184, 3320, 2360, 1784 ),
		array( 4712, 3624, 2600, 2024 ),
		array( 5176, 4056, 2936, 2264 ),
		array( 5768, 4504, 3176, 2504 ),
		array( 6360, 5016, 3560, 2728 ),
		array( 6888, 5352, 3880, 3080 ),
		array( 7456, 5712, 4096, 3248 ),
		array( 8048, 6256, 4544, 3536 ),
		array( 8752, 6880, 4912, 3712 ),
		array( 9392, 7312, 5312, 4112 ),
		array( 10208, 8000, 5744, 4304 ),
		array( 10960, 8496, 6032, 4768 ),
		array( 11744, 9024, 6464, 5024 ),
		array( 12248, 9544, 6968, 5288 ),
		array( 13048, 10136, 7288, 5608 ),
		array( 13880, 10984, 7880, 5960 ),
		array( 14744, 11640, 8264, 6344 ),
		array( 15640, 12328, 8920, 6760 ),
		array( 16568, 13048, 7208, 9368 ),
		array( 17528, 13800, 9848, 7688 ),
		array( 18448, 14496, 10288, 7888 ),
		array( 19472, 15312, 10832, 8432 ),
		array( 20528, 15936, 11408, 8768 ),
		array( 21616, 16816, 12016, 9136 ),
		array( 22496, 17728, 12656, 9776 ),
		array( 23648, 18672, 13328, 10208 ),
	);

	/**
	 * @link http://www.thonky.com/qr-code-tutorial/error-correction-table
	 */
	const RSBLOCKS = array(
	null, // start at 1
		array( array( 1, 0, 26, 19 ), array( 1, 0, 26, 16 ), array( 1, 0, 26, 13 ), array( 1, 0, 26, 9 ) ), //  1
		array( array( 1, 0, 44, 34 ), array( 1, 0, 44, 28 ), array( 1, 0, 44, 22 ), array( 1, 0, 44, 16 ) ), //
		array( array( 1, 0, 70, 55 ), array( 1, 0, 70, 44 ), array( 2, 0, 35, 17 ), array( 2, 0, 35, 13 ) ), //
		array( array( 1, 0, 100, 80 ), array( 2, 0, 50, 32 ), array( 2, 0, 50, 24 ), array( 4, 0, 25, 9 ) ), //
		array( array( 1, 0, 134, 108 ), array( 2, 0, 67, 43 ), array( 2, 2, 33, 15 ), array( 2, 2, 33, 11 ) ), //  5
		array( array( 2, 0, 86, 68 ), array( 4, 0, 43, 27 ), array( 4, 0, 43, 19 ), array( 4, 0, 43, 15 ) ), //
		array( array( 2, 0, 98, 78 ), array( 4, 0, 49, 31 ), array( 2, 4, 32, 14 ), array( 4, 1, 39, 13 ) ), //
		array( array( 2, 0, 121, 97 ), array( 2, 2, 60, 38 ), array( 4, 2, 40, 18 ), array( 4, 2, 40, 14 ) ), //
		array( array( 2, 0, 146, 116 ), array( 3, 2, 58, 36 ), array( 4, 4, 36, 16 ), array( 4, 4, 36, 12 ) ), //
		array( array( 2, 2, 86, 68 ), array( 4, 1, 69, 43 ), array( 6, 2, 43, 19 ), array( 6, 2, 43, 15 ) ), // 10
		array( array( 4, 0, 101, 81 ), array( 1, 4, 80, 50 ), array( 4, 4, 50, 22 ), array( 3, 8, 36, 12 ) ), //
		array( array( 2, 2, 116, 92 ), array( 6, 2, 58, 36 ), array( 4, 6, 46, 20 ), array( 7, 4, 42, 14 ) ), //
		array( array( 4, 0, 133, 107 ), array( 8, 1, 59, 37 ), array( 8, 4, 44, 20 ), array( 12, 4, 33, 11 ) ), //
		array( array( 3, 1, 145, 115 ), array( 4, 5, 64, 40 ), array( 11, 5, 36, 16 ), array( 11, 5, 36, 12 ) ), //
		array( array( 5, 1, 109, 87 ), array( 5, 5, 65, 41 ), array( 5, 7, 54, 24 ), array( 11, 7, 36, 12 ) ), // 15
		array( array( 5, 1, 122, 98 ), array( 7, 3, 73, 45 ), array( 15, 2, 43, 19 ), array( 3, 13, 45, 15 ) ), //
		array( array( 1, 5, 135, 107 ), array( 10, 1, 74, 46 ), array( 1, 15, 50, 22 ), array( 2, 17, 42, 14 ) ), //
		array( array( 5, 1, 150, 120 ), array( 9, 4, 69, 43 ), array( 17, 1, 50, 22 ), array( 2, 19, 42, 14 ) ), //
		array( array( 3, 4, 141, 113 ), array( 3, 11, 70, 44 ), array( 17, 4, 47, 21 ), array( 9, 16, 39, 13 ) ), //
		array( array( 3, 5, 135, 107 ), array( 3, 13, 67, 41 ), array( 15, 5, 54, 24 ), array( 15, 10, 43, 15 ) ), // 20
		array( array( 4, 4, 144, 116 ), array( 17, 0, 68, 42 ), array( 17, 6, 50, 22 ), array( 19, 6, 46, 16 ) ), //
		array( array( 2, 7, 139, 111 ), array( 17, 0, 74, 46 ), array( 7, 16, 54, 24 ), array( 34, 0, 37, 13 ) ), //
		array( array( 4, 5, 151, 121 ), array( 4, 14, 75, 47 ), array( 11, 14, 54, 24 ), array( 16, 14, 45, 15 ) ), //
		array( array( 6, 4, 147, 117 ), array( 6, 14, 73, 45 ), array( 11, 16, 54, 24 ), array( 30, 2, 46, 16 ) ), //
		array( array( 8, 4, 132, 106 ), array( 8, 13, 75, 47 ), array( 7, 22, 54, 24 ), array( 22, 13, 45, 15 ) ), // 25
		array( array( 10, 2, 142, 114 ), array( 19, 4, 74, 46 ), array( 28, 6, 50, 22 ), array( 33, 4, 46, 16 ) ), //
		array( array( 8, 4, 152, 122 ), array( 22, 3, 73, 45 ), array( 8, 26, 53, 23 ), array( 12, 28, 45, 15 ) ), //
		array( array( 3, 10, 147, 117 ), array( 3, 23, 73, 45 ), array( 4, 31, 54, 24 ), array( 11, 31, 45, 15 ) ), //
		array( array( 7, 7, 146, 116 ), array( 21, 7, 73, 45 ), array( 1, 37, 53, 23 ), array( 19, 26, 45, 15 ) ), //
		array( array( 5, 10, 145, 115 ), array( 19, 10, 75, 47 ), array( 15, 25, 54, 24 ), array( 23, 25, 45, 15 ) ), // 30
		array( array( 13, 3, 145, 115 ), array( 2, 29, 74, 46 ), array( 42, 1, 54, 24 ), array( 23, 28, 45, 15 ) ), //
		array( array( 17, 0, 145, 115 ), array( 10, 23, 74, 46 ), array( 10, 35, 54, 24 ), array( 19, 35, 45, 15 ) ), //
		array( array( 17, 1, 145, 115 ), array( 14, 21, 74, 46 ), array( 29, 19, 54, 24 ), array( 11, 46, 45, 15 ) ), //
		array( array( 13, 6, 145, 115 ), array( 14, 23, 74, 46 ), array( 44, 7, 54, 24 ), array( 59, 1, 46, 16 ) ), //
		array( array( 12, 7, 151, 121 ), array( 12, 26, 75, 47 ), array( 39, 14, 54, 24 ), array( 22, 41, 45, 15 ) ), // 35
		array( array( 6, 14, 151, 121 ), array( 6, 34, 75, 47 ), array( 46, 10, 54, 24 ), array( 2, 64, 45, 15 ) ), //
		array( array( 17, 4, 152, 122 ), array( 29, 14, 74, 46 ), array( 49, 10, 54, 24 ), array( 24, 46, 45, 15 ) ), //
		array( array( 4, 18, 152, 122 ), array( 13, 32, 74, 46 ), array( 48, 14, 54, 24 ), array( 42, 32, 45, 15 ) ), //
		array( array( 20, 4, 147, 117 ), array( 40, 7, 75, 47 ), array( 43, 22, 54, 24 ), array( 10, 67, 45, 15 ) ), //
		array( array( 19, 6, 148, 118 ), array( 18, 31, 75, 47 ), array( 34, 34, 54, 24 ), array( 20, 61, 45, 15 ) ), // 40
	);

	/**
	 * @param string $data
	 *
	 * @return \chillerlan\QRCode\Data\QRDataInterface
	 */
	public function setData( string $data );

	/**
	 * @param int  $maskPattern
	 * @param bool $test
	 *
	 * @return \chillerlan\QRCode\Data\QRMatrix
	 */
	public function initMatrix( int $maskPattern, bool $test = null ): QRMatrix;
}
