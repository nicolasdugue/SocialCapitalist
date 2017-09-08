# usage: python logreg.py feature1 feature2 ... feature20

from sys import argv,exit
from numpy import array,zeros,log,exp

feat=argv[1:len(argv)]

n0=len(feat)

if n0!=20:
 exit("ERROR: number of features = "+str(n0))

MEAN= array([ 7.38372241196 , 1.16797099164 , 3.51465803555 , 5.99178850593 , 6.3511552633 , 5.2212995739 , 4.35496419616 , 0.493610941701 , 0.161082807223 , 0.559018181135 , 3.73071610718 , 0.0 , 0.0 , 8.76345457428 , 0.0336864938405 , 0.0388327600288 , 0.181326996217 , 0.001217443024 , 0.0615562878254 , 0.391523174927 , 60.1382675328 , 9.6429787319 , 29.3013852029 , 45.818139569 , 49.2222246973 , 40.792106636 , 32.6985559913 , 3.87968996034 , 1.224140593 , 4.33576339393 , 29.1241307334 , 0.0 , 0.0 , 64.0025425175 , 0.26749763472 , 0.338211512211 , 1.17653861908 , 0.0104247762416 , 0.45746299338 , 3.0312921947 , 3.3248574974 , 4.5116371676 , 7.8302815825 , 8.80473934421 , 7.11490297992 , 5.23459074376 , 0.554550458379 , 0.280265272691 , 0.670319479691 , 3.5577546454 , 0.0 , 0.0 , 10.2621088461 , 0.0883030688805 , 0.0511227917049 , 0.242541908062 , 0.00266844858426 , 0.107993083086 , 0.354832872125 , 19.3508943856 , 22.8666337521 , 24.070287492 , 20.8638580434 , 15.4617243235 , 1.71821138532 , 0.46664233161 , 2.24475739753 , 15.8220011821 , 0.0 , 0.0 , 30.1433654093 , 0.100374733246 , 0.0686408654456 , 0.515715541815 , 0.00369079353582 , 0.168135669704 , 1.70423047855 , 38.9403508021 , 39.8832217962 , 34.6393730104 , 26.3472769213 , 3.0259685041 , 0.984308723214 , 3.46313828919 , 22.9563677714 , 0.0 , 0.0 , 52.7434228856 , 0.212559831399 , 0.207001739428 , 1.08851091407 , 0.0079931223055 , 0.373362363732 , 2.40339807961 , 43.1281738316 , 35.6203510568 , 27.9348523817 , 3.26018547393 , 1.06786054522 , 3.66001446522 , 24.0573408734 , 0.0 , 0.0 , 55.3352262508 , 0.237408208759 , 0.266243614328 , 1.12231179838 , 0.00840854781576 , 0.393491792632 , 2.49997309877 , 31.6035665162 , 23.0207338217 , 2.66672266275 , 0.84141171494 , 3.0745126818 , 20.3073895244 , 0.0 , 0.0 , 45.8749318049 , 0.187502167071 , 0.167261376248 , 0.926713639186 , 0.00702873217722 , 0.316801232128 , 2.15237828896 , 19.3342553312 , 2.26896123059 , 0.727579377887 , 2.50549099311 , 16.5387832414 , 0.0 , 0.0 , 38.6545865288 , 0.152411967036 , 0.178883050791 , 0.793786761941 , 0.00550833694496 , 0.273870921119 , 1.7216368308 , 0.48347527186 , 0.0802175496901 , 0.324489440474 , 2.03286131008 , 0.0 , 0.0 , 4.17609874101 , 0.0147884499572 , 0.03116611804 , 0.071010155085 , 0.00118297032177 , 0.0236434667865 , 0.199712740034 , 0.0676549682015 , 0.0787663341668 , 0.452358666225 , 0.0 , 0.0 , 1.44926893686 , 0.0100474310747 , 0.0140674201361 , 0.0274745466153 , 0.000517505411893 , 0.027575373977 , 0.040008486634 , 0.411947476015 , 2.40089697373 , 0.0 , 0.0 , 4.85629924676 , 0.0178781916234 , 0.0192091889256 , 0.0931952475219 , 0.00044725377143 , 0.0228398484279 , 0.252194244708 , 20.1906565383 , 0.0 , 0.0 , 32.5893344254 , 0.0767295869572 , 0.132124392082 , 0.574354057162 , 0.00261978326512 , 0.133218635679 , 1.79731933312 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 80.8712857876 , 0.308513489343 , 0.29448738589 , 1.68010611324 , 0.0105696282398 , 0.577916794778 , 3.45687356953 , 0.01751824238 , 0.000435357133108 , 0.00302860145562 , 8.14989049547e-05 , 0.00175180511101 , 0.00295644304649 , 0.0219640253734 , 0.00147045526128 , 4.88159763784e-05 , 0.000553758856592 , 0.00357631934658 , 0.0990518176295 , 6.81005769605e-05 , 0.00791296749919 , 0.0242785088367 , 0.00057378815576 , 7.78372110807e-05 , 8.315992431e-05 , 0.0261801725639 , 0.0108963637954 , 0.243257760256 , 0.0 ])

STD=array([ 2.37042419745 , 1.40025042763 , 2.64538716992 , 1.7432215299 , 1.67062881964 , 2.0836499888 , 0.6070767508 , 0.489717786172 , 0.204223645592 , 0.315350835063 , 2.50447876133 , 1.0 , 1.0 , 2.01820457637 , 0.127997900423 , 0.143024620684 , 0.257239845421 , 0.0239229176365 , 0.149636212172 , 0.29994560132 , 31.0842671854 , 12.5783512227 , 25.3189160064 , 21.558270114 , 22.8896579072 , 22.803369035 , 10.8284341883 , 4.13740888211 , 1.6497769006 , 2.89514324924 , 21.3840879057 , 1.0 , 1.0 , 18.9942466117 , 1.06394567729 , 1.2805486728 , 1.79566266684 , 0.210177476475 , 1.16582599377 , 2.53783714788 , 6.35284166792 , 7.6801706321 , 10.4371144867 , 11.9085570755 , 9.90230448577 , 6.37007857269 , 1.00432276027 , 0.605953261244 , 1.08740204048 , 5.59503795454 , 1.0 , 1.0 , 12.7599908691 , 0.41208165643 , 0.272231980358 , 0.57538190666 , 0.0592707716124 , 0.36730063787 , 0.609418813403 , 21.8154196446 , 19.5324294576 , 20.6085275685 , 18.9650919066 , 11.6391926507 , 2.26034718299 , 0.735016419604 , 2.22502911532 , 16.2226513549 , 1.0 , 1.0 , 22.1909020671 , 0.496153026985 , 0.332348657908 , 1.0598097979 , 0.0951717484187 , 0.471999318705 , 1.80226035025 , 19.8438178405 , 19.5181495957 , 20.6178611776 , 8.54540819742 , 3.25852988329 , 1.34721141273 , 2.42539665838 , 17.0125154437 , 1.0 , 1.0 , 17.620688015 , 0.836377447875 , 0.827784858148 , 1.63575408583 , 0.16289685233 , 0.936347380726 , 1.9714748189 , 21.2139682106 , 20.8099182641 , 8.44400977474 , 3.44476714842 , 1.44418634855 , 2.5094107685 , 17.2921407063 , 1.0 , 1.0 , 16.8934536573 , 0.929302186563 , 0.994862676912 , 1.67707098145 , 0.171651605451 , 0.987667623651 , 2.05225594594 , 21.1315523887 , 9.74371671378 , 3.09322657822 , 1.2106277721 , 2.3989805034 , 16.1047414564 , 1.0 , 1.0 , 18.8054729155 , 0.75739858899 , 0.738270204312 , 1.4624890582 , 0.148346370487 , 0.811287090808 , 1.86698950602 , 3.53103220123 , 2.33724114416 , 0.933443260207 , 1.49519165832 , 11.1622944927 , 1.0 , 1.0 , 8.95199740161 , 0.580021954317 , 0.663658723431 , 1.12937116574 , 0.108368412126 , 0.668774007952 , 1.32720634162 , 0.809822058467 , 0.159401689293 , 0.400836731792 , 2.4064015596 , 1.0 , 1.0 , 4.12416469899 , 0.0834291080984 , 0.144517855632 , 0.173651828354 , 0.0307847525286 , 0.0957318933442 , 0.281515856451 , 0.137883499592 , 0.111269269021 , 0.657039711963 , 1.0 , 1.0 , 1.89305436241 , 0.0520372819609 , 0.0652760047193 , 0.0676305806724 , 0.0122471557451 , 0.0879883333325 , 0.0626912059668 , 0.429219391961 , 1.93839980686 , 1.0 , 1.0 , 2.7134155669 , 0.0792742829834 , 0.0837395253382 , 0.167315607761 , 0.0110102838305 , 0.0601252164849 , 0.239003607234 , 20.5209609054 , 1.0 , 1.0 , 22.9931340804 , 0.405627172449 , 0.590063332085 , 1.12569825398 , 0.0739107957118 , 0.402616708536 , 1.75832462723 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 1.0 , 29.3139250017 , 1.17640609088 , 1.07120866792 , 2.47128666703 , 0.208904775301 , 1.41487258742 , 2.70052214061 , 0.0776945913342 , 0.00596733232417 , 0.0163743941631 , 0.0027351174962 , 0.0116572671799 , 0.0159877202202 , 0.0957027973793 , 0.00963150012 , 0.00208128251806 , 0.00599450106894 , 0.0156322503702 , 0.168378681265 , 0.00226548360327 , 0.0245897580655 , 0.0449241774192 , 0.0143519187809 , 0.00230075367166 , 0.0021983052143 , 0.0893850646237 , 0.0280302234277 , 0.211510613269 , 1.0 ])

COEF=array([ 0.834630442759 , -2.65284627028 , 1.27542298093 , -0.18609164061 , 2.46160423408 , -1.69661029231 , 0.187268533904 , 2.08055526991 , 0.226364868529 , 1.75423249747 , 2.4134327425 , 0.0 , 0.0 , 1.34850657228 , -0.0792369315979 , 1.40883292577 , -1.21093614525 , 0.211146823868 , -0.615969774403 , 0.271244575732 , -0.146601790396 , 1.10405595783 , -1.22786879866 , -0.914806224736 , 1.79867762141 , -1.06476610594 , 1.18034701271 , 2.69768815314 , -0.49237244517 , -0.0653668189386 , -0.584667707286 , 0.0 , 0.0 , -2.27406532936 , 0.348994069695 , 0.111413949659 , 0.291546544201 , 0.0406364628806 , 0.235921532906 , 0.177856980474 , -0.972136869369 , 0.332897187633 , -1.33273790043 , 1.12991026906 , 1.8975747242 , -1.68763889423 , -0.202821194952 , -0.227750391984 , -0.0175708551605 , -0.0379662325258 , 0.0 , 0.0 , 1.4175831039 , -0.0500786802737 , 0.0326663349122 , -0.166789324321 , -0.454899857882 , 0.0548532590339 , -0.260489366785 , -0.286787913783 , 1.54324526944 , 0.527710030131 , -1.49940398372 , 0.957688410564 , -0.564320033122 , 0.17155134634 , -0.0289947748905 , 0.10609753425 , 0.0 , 0.0 , -0.586980210696 , -0.195189757885 , -0.113594131587 , -0.170385996844 , 0.170293621377 , -0.163840825422 , -0.815312180179 , -1.02928460377 , 0.777796360344 , -2.28688208829 , 0.724048936085 , 0.883722085015 , -0.48786694992 , 1.25951559129 , -0.481728946749 , 0.0 , 0.0 , -0.876912619216 , -0.144664495554 , 0.959950376408 , -0.735470069321 , -0.265659768474 , 0.485890670282 , -0.0366190325256 , -3.85773377611 , 3.25312775584 , 0.0779191639415 , -2.01641775315 , -0.628657188252 , -0.654441132719 , 0.943870073611 , 0.0 , 0.0 , 0.370457607917 , -0.167837618815 , -1.25177195046 , -0.242656012868 , 1.20077933736 , -0.537445070102 , -0.272291628116 , 3.15087852847 , 0.0382340801499 , 0.158290798974 , 0.745940811425 , -1.37582994535 , -0.451784653875 , 0.0 , 0.0 , -0.0881277053765 , 0.17256663417 , -0.403895067729 , 1.2034702585 , -0.10754791012 , -0.00624398709959 , 1.20654633532 , -1.0573760634 , 0.415796870239 , -0.0871896491753 , -1.6013084816 , -1.26063046889 , 0.0 , 0.0 , 0.363071530559 , 0.101162618489 , 1.20723338818 , -0.688497176359 , 0.213660128874 , 0.690222288755 , -0.5352076081 , -1.1036734227 , -0.197052407557 , 0.295702585109 , 0.408658002776 , 0.0 , 0.0 , 0.579648731305 , -0.244676295503 , -0.129378860194 , -0.513395869123 , -0.00794050878698 , -0.47028156893 , -0.970728440731 , 0.335357370875 , 0.183824215868 , -0.0913958503954 , 0.0 , 0.0 , -0.142322067262 , 0.291672060165 , 0.187017822801 , 0.0703970155602 , -0.110772956091 , 0.0353758103491 , 0.0948451118266 , 0.245339576172 , 0.167350239511 , 0.0 , 0.0 , -0.143356873455 , 0.166111515894 , 0.261221183379 , 0.237189330613 , -0.0569482853735 , 0.0165443486708 , 0.196774165848 , -0.76789736247 , 0.0 , 0.0 , -0.0266649066717 , 0.114981912794 , 0.0460889147186 , 0.352732689825 , -0.286169478488 , 0.0151466286597 , 0.437444221557 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , 0.0 , -1.60999236459 , -0.327168427287 , -0.489297051313 , 1.16395439524 , -0.755094437805 , -0.0263628123662 , 0.257314793417 , -0.0676303354523 , -0.0419841417965 , 0.0699344138713 , 0.0673313225749 , -0.0648202296999 , 0.0223859886043 , -1.21495926277 , 0.0118046060754 , 0.0554868490903 , -0.0803765906834 , 0.0177824844937 , 0.342814913128 , -0.165215104477 , 0.110484890506 , -0.141613042442 , 0.0811646398154 , -0.00813980353254 , 0.0817140350456 , 0.320537149942 , -0.136773051708 , -0.058526875221 , 0.231846712366 ])



n=len(MEAN)
X=zeros(n)

for i in xrange(n0):
	X[i]=log(1+float(feat[i]))

k=n0
for i in xrange(n0):
	for j in xrange(i,n0):
		X[k]=X[i]*X[j]
		k+=1

X=(X-MEAN)/STD

X[n-1]=1.

print 1./(1.+exp(-sum(COEF*X)))


