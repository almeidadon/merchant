<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* 
1.LOGIN()							34
2.keepAlive()						63
3.generateOtp()						90
4.customerRegistration()			128
5.queryRegistration()				186
6.updateCustomer()					220					
7.transactionRequest()				267
8.queryTransactionReq()				311
9.balanceEnquiryReq()				345
10.accountBlockReq()				390
11.accountUnblockReq()				421
12.beneficiaryRegistrationReq()		452
13.queryBeneficiary()				496
14.queryBeneficiaryList()			533
15.deactivateBeneficiaryReq()		569
16.remittanceTransactionReq()		607
17.queryRemittanceReq()				650
18.walletTransferReq()				684
19.queryTransferReq()				726
20.logoff()							765
*/

class Wallet_shmart
{
	function __construct()
	{
		
		$this->ci =& get_instance();
		$this->ci->load->model('payment_gateway/logging_model');
		$this->ci->load->model('payment_gateway/payment_gateway_model');
		$this->ci->load->model('payment_gateway/consumer_model');
        $this->ci->load->library('paymentgateway');
        $this->ci->load->library('tank_auth');
        $this->ci->load->library('notification_lib');

        define("UID",     "shmartmoney");
		define("PWD",    "28rf3a4557510c82d47d0ac1a5244c4eba0762b3");
		define("API_ENDPOINT", "http://uat-api.shmart.in/services/smp");
		define("PRODUCT_CODE", "24");
		$SessionID = $this->ci->payment_gateway_model->getWalletSession();
        define("SessionID",$SessionID);
    }
	/*
	 * voucher_type
	 * trans_voucher_wallet_amount
	 * mobileNo
	 * expiry_date
	 * narrations
	 * merchant_id
	*/
	function createVoucher($data)
	{
        $data['voucher_id'] = $this->ci->consumer_model->generateVoucherID();
        $data['voucher_amount'] = $data['trans_voucher_wallet_amount'];
        $data['voucher_wallet_txnNo'] =  $data['txnNo'] = $this->ci->paymentgateway->generateRefID();
        $data['is_used'] =  0;
        $data['is_deleted'] =  0;
        $data['is_expired'] =  0;
        $credit_response = $this->creditVoucherWallet($data);
        if($credit_response['ResponseCode'] == '0')
        {
            $data['ackNo'] =  $credit_response['AckNo'];
            unset($data['trans_voucher_wallet_amount']);
            unset($data['voucher_wallet_txnNo']);
            $data['voucher_amount'] = ($data['voucher_amount']/100);
            $data['voucher_amount'] = number_format( $data['voucher_amount'] , 2);
            $this->ci->consumer_model->createNewVoucherForApi($data);
            return 1;
        }
        else
        {
            return 0;
        }
    }
    /*
     * Create shmart customer.Creates a wallet as well as create a shmart dashboard login
     * Parameters to be passed
     *mobileNo
     *email
	 *name
     *  */
    function createShmartCustomer($data)
    {
		$data['name_of_customer'] = $data['f_name'] = $data['name_on_card'];
        $data['email_activation'] = 0 ;
        $data['password'] = trim($this->generateStrongPassword());
		$data['l_name'] = $data['addr'] = $data['city'] = $data['state'] = $data['country'] = $data['zipcode'] = '';
		$data['username'] = $data['mobileNo'];
        $user_created = $this->ci->tank_auth->create_user($data['mobileNo'],$data['email'],$data['password'],$data['name_on_card'],'S',$data['email_activation']);
		if($user_created)
        {
            $this->customerRegistration($data);
			return $data;
        }
		else
		{
         return 0;
		}
    }
    function generateStrongPassword()
    {
        $adjectives = "red,white,blue,green,orange,yellow,pink,purple,violet,happy,silly,abandoned, able, absolute, adorable, adventurous, academic, acceptable, acclaimed, accomplished, accurate, aching, acidic, acrobatic";
        $adjectives .= "babyish, bad, back, baggy, bare, barren, basic, beautiful, belated, beloved, beneficial, better, best, bewitched, big, big-hearted, biodegradable, bite-sized, bitter, black, black-and-white, bland, blank, blaring, bleak, blind, blissful, blond, blue, blushing, bogus, boiling, bold, bony, boring, bossy, both, bouncy, bountiful, bowed, brave, breakable, brief, bright, brilliant, brisk, broken, bronze, brown, bruised, bubbly, bulky, bumpy, buoyant, burdensome, burly, bustling, busy, buttery, buzzing";
        $adjectives .= "calculating, calm, candid, canine, capital, carefree, careful, careless, caring, cautious, cavernous, celebrated, charming, cheap, cheerful, cheery, chief, chilly, chubby, circular, classic, clean, clear, clear-cut, clever, close, closed, cloudy, clueless, clumsy, cluttered, coarse, cold, colorful, colorless, colossal, comfortable, common, compassionate, competent, complete, complex, complicated, composed, concerned, concrete, confused, conscious, considerate, constant, content, conventional, cooked, cool, cooperative, coordinated, corny, corrupt, costly, courageous, courteous, crafty, crazy, creamy, creative, creepy, criminal, crisp, critical, crooked, crowded, cruel, crushing, cuddly, cultivated, cultured, cumbersome, curly, curvy, cute, cylindrical, , D, damaged, damp, dangerous, dapper, daring, darling, dark, dazzling, dead, deadly, deafening, dear, dearest, decent, decimal, decisive, deep, defenseless, defensive, defiant, deficient, definite, definitive, delayed, delectable, delicious, delightful, delirious, demanding, dense, dental, dependable, dependent, descriptive, deserted, detailed, determined, devoted, different, difficult, digital, diligent, dim, dimpled, dimwitted, direct, disastrous, discrete, disfigured, disgusting, disloyal, dismal, distant, downright, dreary, dirty, disguised, dishonest, dismal, distant, distinct, distorted, dizzy, dopey, doting, double, downright, drab, drafty, dramatic, dreary, droopy, dry, dual, dull, dutiful, , E, each, eager, earnest, early, easy, easy-going, ecstatic, edible, educated, elaborate, elastic, elated, elderly, electric, elegant, elementary, elliptical, embarrassed, embellished, eminent, emotional, empty, enchanted, enchanting, energetic, enlightened, enormous, enraged, entire, envious, equal, equatorial, essential, esteemed, ethical, euphoric, even, evergreen, everlasting, every, evil, exalted, excellent, exemplary, exhausted, excitable, excited, exciting, exotic, expensive, experienced, expert, extraneous, extroverted, extra-large, extra-small, , F, fabulous, failing, faint, fair, faithful, fake, false, familiar, famous, fancy, fantastic, far, faraway, far-flung, far-off, fast, fat, fatal, fatherly, favorable, favorite, fearful, fearless, feisty, feline, female, feminine, few, fickle, filthy, fine, finished, firm, first, firsthand, fitting, fixed, flaky, flamboyant, flashy, flat, flawed, flawless, flickering, flimsy, flippant, flowery, fluffy, fluid, flustered, focused, fond, foolhardy, foolish, forceful, forked, formal, forsaken, forthright, fortunate, fragrant, frail, frank, frayed, free, French, fresh, frequent, friendly, frightened, frightening, frigid, frilly, frizzy, frivolous, front, frosty, frozen, frugal, fruitful, full, fumbling, functional, funny, fussy, fuzzy, gargantuan, gaseous, general, generous, gentle, genuine, giant, giddy, gigantic, gifted, giving, glamorous, glaring, glass, gleaming, gleeful, glistening, glittering, gloomy, glorious, glossy, glum, golden, good, good-natured, gorgeous, graceful, gracious, grand, grandiose, granular, grateful, grave, gray, great, greedy, green, gregarious, grim, grimy, gripping, grizzled, gross, grotesque, grouchy, grounded, growing, growling, grown, grubby, gruesome, grumpy, guilty, gullible, gummy, hairy, half, handmade, handsome, handy, happy, happy-go-lucky, hard, hard-to-find, harmful, harmless, harmonious, harsh, hasty, hateful, haunting, healthy, heartfelt, hearty, heavenly, heavy, hefty, helpful, helpless, hidden, hideous, high, high-level, hilarious, hoarse, hollow, homely, honest, honorable, honored, hopeful, horrible, hospitable, hot, huge, humble, humiliating, humming, humongous, hungry, hurtful, husky, icky, icy, ideal, idealistic, identical, idle, idiotic, idolized, ignorant, ill, illegal, ill-fated, ill-informed, illiterate, illustrious, imaginary, imaginative, immaculate, immaterial, immediate, immense, impassioned, impeccable, impartial, imperfect, imperturbable, impish, impolite, important, impossible, impractical, impressionable, impressive, improbable, impure, inborn, incomparable, incompatible, incomplete, inconsequential, incredible, indelible, inexperienced, indolent, infamous, infantile, infatuated, inferior, infinite, informal, innocent, insecure, insidious, insignificant, insistent, instructive, insubstantial, intelligent, intent, intentional, interesting, internal, international, intrepid, ironclad, irresponsible, irritating, itchy, jaded, jagged, jam-packed, jaunty, jealous, jittery, joint, jolly, jovial, joyful, joyous, jubilant, judicious, juicy, jumbo, junior, jumpy, juvenil, kaleidoscopic, keen, key, kind, kindhearted, kindly, klutzy, knobby, knotty, knowledgeable, knowing, known, kooky, kosher, , L, lame, lanky, large, last, lasting, late, lavish, lawful, lazy, leading, lean, leafy, left, legal, legitimate, light, lighthearted, likable, likely, limited, limp, limping, linear, lined, liquid, little, live, lively, livid, loathsome, lone, lonely, long, long-term, loose, lopsided, lost, loud, lovable, lovely, loving, low, loyal, lucky, lumbering, luminous, lumpy, lustrous, luxurious, , M, mad, made-up, magnificent, majestic, major, male, mammoth, married, marvelous, masculine, massive, mature, meager, mealy, mean, measly, meaty, medical, mediocre, medium, meek, mellow, melodic, memorable, menacing, merry, messy, metallic, mild, milky, mindless, miniature, minor, minty, miserable, miserly, misguided, misty, mixed, modern, modest, moist, monstrous, monthly, monumental, moral, mortified, motherly, motionless, mountainous, muddy, muffled, multicolored, mundane, murky, mushy, musty, muted, mysterious, naive, narrow, nasty, natural, naughty, nautical, near, neat, necessary, needy, negative, neglected, negligible, neighboring, nervous, new, next, nice, nifty, nimble, nippy, nocturnal, noisy, nonstop, normal, notable, noted, noteworthy, novel, noxious, numb, nutritious, nutty, obedient, obese, oblong, oily, oblong, obvious, occasional, odd, oddball, offbeat, offensive, official, old, old-fashioned, only, open, optimal, optimistic, opulent, orange, orderly, organic, ornate, ornery, ordinary, original, other, our, outlying, outgoing, outlandish, outrageous, outstanding, oval, overcooked, overdue, overjoyed, overlooked, , P, palatable, pale, paltry, parallel, parched, partial, passionate, past, pastel, peaceful, peppery, perfect, perfumed, periodic, perky, personal, pertinent, pesky, pessimistic, petty, phony, physical, piercing, pink, pitiful, plain, plaintive, plastic, playful, pleasant, pleased, pleasing, plump, plush, polished, polite, political, pointed, pointless, poised, poor, popular, portly, posh, positive, possible, potable, powerful, powerless, practical, precious, present, prestigious, pretty, precious, previous, pricey, prickly, primary, prime, pristine, private, prize, probable, productive, profitable, profuse, proper, proud, prudent, punctual, pungent, puny, pure, purple, pushy, putrid, puzzled, puzzling, , Q, quaint, qualified, quarrelsome, quarterly, queasy, querulous, questionable, quick, quick-witted, quiet, quintessential, quirky, quixotic, quizzical, radiant, ragged, rapid, rare, rash, raw, recent, reckless, rectangular, ready, real, realistic, reasonable, red, reflecting, regal, regular, reliable, relieved, remarkable, remorseful, remote, repentant, required, respectful, responsible, repulsive, revolving, rewarding, rich, rigid, right, ringed, ripe, roasted, robust, rosy, rotating, rotten, rough, round, rowdy, royal, rubbery, rundown, ruddy, rude, runny, rural, rusty, sad, safe, salty, same, sandy, sane, sarcastic, sardonic, satisfied, scaly, scarce, scared, scary, scented, scholarly, scientific, scornful, scratchy, scrawny, second, secondary, second-hand, secret, self-assured, self-reliant, selfish, sentimental, separate, serene, serious, serpentine, several, severe, shabby, shadowy, shady, shallow, shameful, shameless, sharp, shimmering, shiny, shocked, shocking, shoddy, short, short-term, showy, shrill, shy, sick, silent, silky, silly, silver, similar, simple, simplistic, sinful, single, sizzling, skeletal, skinny, sleepy, slight, slim, slimy, slippery, slow, slushy, small, smart, smoggy, smooth, smug, snappy, snarling, sneaky, sniveling, snoopy, sociable, soft, soggy, solid, somber, some, spherical, sophisticated, sore, sorrowful, soulful, soupy, sour, Spanish, sparkling, sparse, specific, spectacular, speedy, spicy, spiffy, spirited, spiteful, splendid, spotless, spotted, spry, square, squeaky, squiggly, stable, staid, stained, stale, standard, starchy, stark, starry, steep, sticky, stiff, stimulating, stingy, stormy, straight, strange, steel, strict, strident, striking, striped, strong, studious, stunning, stupendous, stupid, sturdy, stylish, subdued, submissive, substantial, subtle, suburban, sudden, sugary, sunny, super, superb, superficial, superior, supportive, sure-footed, surprised, suspicious, svelte, sweaty, sweet, sweltering, swift, sympathetic, tall, talkative, tame, tan, tangible, tart, tasty, tattered, taut, tedious, teeming, tempting, tender, tense, tepid, terrible, terrific, testy, thankful, that, these, thick, thin, third, thirsty, this, thorough, thorny, those, thoughtful, threadbare, thrifty, thunderous, tidy, tight, timely, tinted, tiny, tired, torn, total, tough, traumatic, treasured, tremendous, tragic, trained, tremendous, triangular, tricky, trifling, trim, trivial, troubled, true, trusting, trustworthy, trusty, truthful, tubby, turbulent, twin, ugly, ultimate, unacceptable, unaware, uncomfortable, uncommon, unconscious, understated, unequaled, uneven, unfinished, unfit, unfolded, unfortunate, unhappy, unhealthy, uniform, unimportant, unique, united, unkempt, unknown, unlawful, unlined, unlucky, unnatural, unpleasant, unrealistic, unripe, unruly, unselfish, unsightly, unsteady, unsung, untidy, untimely, untried, untrue, unused, unusual, unwelcome, unwieldy, unwilling, unwitting, unwritten, upbeat, upright, upset, urban, usable, used, useful, useless, utilized, utter, vacant, vague, vain, valid, valuable, vapid, variable, vast, velvety, venerated, vengeful, verifiable, vibrant, vicious, victorious, vigilant, vigorous, villainous, violet, violent, virtual, virtuous, visible, vital, vivacious, vivid, voluminous, , W, wan, warlike, warm, warmhearted, warped, wary, wasteful, watchful, waterlogged, watery, wavy, wealthy, weak, weary, webbed, wee, weekly, weepy, weighty, weird, welcome, well-documented, well-groomed, well-informed, well-lit, well-made, well-off, well-to-do, well-worn, wet, which, whimsical, whirlwind, whispered, white, whole, whopping, wicked, wide, wide-eyed, wiggly, wild, willing, wilted, winding, windy, winged, wiry, wise, witty, wobbly, woeful, wonderful, wooden, woozy, wordy, worldly, worn, worried, worrisome, worse, worst, worthless, worthwhile, worthy, wrathful, wretched, writhing, wrong, yawning, yearly, yellow, yellowish, young, youthful, yummy, zany, zealous, zesty, zigzag";
        $nouns =  "pony,kitten,puppy,house,mouse,horse,cat,dog,duck,fish,pizza,monkey,zebra,";
        $nouns .= "elephant,chicken,sheep,rhino,tree,flower,flamingo,robin,sparrow,lion,tiger,";
        $nouns .= "bear,fox,deer,cow,piglet,turkey,donkey,goat,eagle,swan,owl,penguin,whale,dolphin";
        $nouns = explode(',', $nouns);
        $adjectives = explode(',', $adjectives);
        mt_srand((double)microtime()*1000000);
        $a = mt_rand(0, count($adjectives)-1);
        $n = mt_rand(0, count($nouns)-1);
        $pwd =  $adjectives[$a] . $nouns[$n];
        return $pwd;
    }
	
	function login()																					//1
	{ 
		$soap_do = curl_init();
		curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($soap_do, CURLOPT_POST,true);
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
		<SOAP-ENV:Body>
			<sas:Login>
			<Username>".UID."</Username>
			<Password>".PWD."</Password>
			</sas:Login>
		</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>");
		$response = curl_exec($soap_do);
		$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:LoginResponse/return') as $item)
		{
		  $response =  (array) $item;
		}
        $res = $this->ci->payment_gateway_model->insertNewWalletSession($response);
        $response['creationTime'] = date('Y-m-d H:i:s');
        $this->ci->logging_model->logLoginSession($response);
        if($res)
        {
            return $response['SessionID'];
        } 
	}
	function generateWalletReferenceID()
	{
		return $this->ci->consumer_model->generateWalletRefID();
	}
	
	function keepAlive($sessionID)																					//2
	{
		$soap_do = curl_init();
		curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($soap_do, CURLOPT_POST,true);            
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
		<SOAP-ENV:Body>
			<sas:EchoMessage>
				<SessionID>".SessionID."</SessionID>
			</sas:EchoMessage>
		</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>");
		$response = curl_exec($soap_do);
		$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:EchoMessageResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
	function generateOtp($data)																					//3
	{
		$soap_do = curl_init();
		curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($soap_do, CURLOPT_POST,true);            
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
		<SOAP-ENV:Body>
			<sas:GenerateOTPRequest>
				<SessionID>".SessionID."</SessionID>
				<Mobile>".$data['mobileNo']."</Mobile>
				<RequestType>".$data['reqType']."</RequestType>
				<ProductCode>".PRODUCT_CODE."</ProductCode>
				<Narration></Narration>
				<IsOriginal>N</IsOriginal>
				<OriginalAckNo>1</OriginalAckNo>
				<Filler1>M</Filler1>
				<Filler2></Filler2>
				<Filler3></Filler3>
				<Filler4></Filler4>
				<Filler5></Filler5>
				</sas:GenerateOTPRequest>
		</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>");
		$response = curl_exec($soap_do);
		$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:GenerateOTPRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
	/*
	 * 
	 * 
	*/
	function customerRegistration($data)																					//4
	{
		$txnNo=$this->generateWalletReferenceID();
        $data['par_ref_no'] = $this->ci->consumer_model->generatePartnerRefNo($data['mobileNo']);
		if($data['par_ref_no'])
		{
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:RegistrationRequest>
					<SessionID>".SessionID."</SessionID>
					<TransactionRefNo>".$txnNo."</TransactionRefNo>
					<PartnerRefNo>".$data['par_ref_no']."</PartnerRefNo>
					<CardPackId></CardPackId>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<Title></Title>
					<FirstName>".$data['f_name']."</FirstName>
					<MiddleName></MiddleName>
					<LastName>".$data['l_name']."</LastName>
					<Gender></Gender>
					<DateOfBirth>1991-10-08</DateOfBirth>
					<Mobile>".$data['mobileNo']."</Mobile>
					<Mobile2></Mobile2>
					<Email>".$data['email']."</Email>
					<MotherMaidenName></MotherMaidenName>
					<Landline></Landline>
					<AddressLine1>".$data['addr']."</AddressLine1>
					<AddressLine2>demo address2</AddressLine2>
					<City>".$data['city']."</City>
					<State>".$data['state']."</State>
					<Country>".$data['country']."</Country>
					<Pincode>".$data['zipcode']."</Pincode>
					<IsCardActivated>N</IsCardActivated>
					<ActivationDate></ActivationDate>
					<IsCardDispatch>N</IsCardDispatch>
					<CardDispatchDate></CardDispatchDate>
					<SMSFlag>Y</SMSFlag>
					<Filler1></Filler1>
					<Filler2>non-kyc</Filler2>
					<Filler3>M</Filler3>
					<Filler4></Filler4>
					<Filler5></Filler5>
				</sas:RegistrationRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:RegistrationRequestResponse/return') as $item)
			{
			  $response =  (array) $item;
			}
			$response['mobileNo'] = $data['mobileNo'];
			$this->ci->logging_model->walletRegistrationLogging($response);
			return $response;
		} else {
			return 0;
		}
		
	}
	function queryRegistration()																					//5
	{
		$soap_do = curl_init();
		curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($soap_do, CURLOPT_POST,true);            
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
		<SOAP-ENV:Body>
			<sas:QueryRegistrationRequest>
				<SessionID>".SessionID."</SessionID>
				<QueryReqNo>73908653</QueryReqNo>
				<TransactionRefNo>1234567891012345</TransactionRefNo>
				<Filler1></Filler1>
				<Filler2></Filler2>
				<Filler3></Filler3>
				<Filler4></Filler4>
				<Filler5></Filler5>
			</sas:QueryRegistrationRequest>
		</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>");
		$response = curl_exec($soap_do);
		$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:QueryRegistrationRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
	function updateCustomer($data)																					//6
	{	
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$txnNo=$this->generateWalletReferenceID();
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:UpdateCustomerRequest>
				  <SessionID>".SessionID."</SessionID>
				  <TransactionRefNo>".$txnNo."</TransactionRefNo>
				  <CustomerIdentifierType>PAR</CustomerIdentifierType>
				  <CustomerNo>".$partnerRefNo."</CustomerNo>
				  <ProductCode>".PRODUCT_CODE."</ProductCode>
				  <Mobile>".$data['uMobileNo']."</Mobile>
				  <Email>".$data['uEmail']."</Email>
				  <Landline>".$data['uLandline']."</Landline>
				  <AddressLine1>".$data['uAddr1']."</AddressLine1>
				  <AddressLine2>demo address2</AddressLine2>
				  <City>".$data['uCity']."</City>
				  <State>".$data['uState']."</State>
				  <Country>".$data['uCountry']."</Country>
				  <Pincode>".$data['uPincode']."</Pincode>
				  <OTP></OTP>
				  <SMSFlag>N</SMSFlag>
				  <Filler1></Filler1>
				  <Filler2></Filler2>
				  <Filler3></Filler3>
				  <Filler4></Filler4>
				  <Filler5></Filler5>
				</sas:UpdateCustomerRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:UpdateCustomerRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $log_updatecustomer['TransactionRefNo'] = $txnNo;
            $log_updatecustomer['PartnerRefNo'] = $response ['CustomerNo'];
            $log_updatecustomer['ackNo'] = $response ['QueryReqNo'];
            $log_updatecustomer['ResponseMessage'] = $response ['ResponseMessage'];
            $log_updatecustomer['ResponseCode'] = $response ['ResponseCode'];
            $this->ci->logging_model->updateCustomerLogging($log_updatecustomer);
            return $response;

        }
	}
	function creditGeneralWallet($data)																					//7
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:TransactionRequest>
					<SessionID>".SessionID."</SessionID>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<TxnIdentifierType>PAR</TxnIdentifierType>
					<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
					<Amount>".$data['trans_general_wallet_amount']."</Amount>
					<Currency>356</Currency>
					<Narration></Narration>
					<WalletCode>SMP924</WalletCode>
					<TxnNo>".$data['general_wallet_txnNo']."</TxnNo>
					<CardType>N</CardType>
					<TxnIndicator>CR</TxnIndicator>
					<OTP></OTP>
					<SMSFlag>N</SMSFlag>
					<Filler1></Filler1>
					<Filler2></Filler2>
					<Filler3></Filler3>
					<Filler4></Filler4>
					<Filler5></Filler5>
				</sas:TransactionRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:TransactionRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $log_wallet_transaction['ackNo'] = $response ['AckNo'];
            $log_wallet_transaction['ResponseCode'] = $response ['ResponseCode'];
            $log_wallet_transaction['ResponseMessage'] = $response ['ResponseMessage'];
            $log_wallet_transaction['wallet_type'] = 'GENERAL';
            $log_wallet_transaction['wallet_code'] = 'SMP924';
            $log_wallet_transaction['amount'] = number_format(($data['trans_general_wallet_amount']/100),2,'.','');
            $log_wallet_transaction['txnNo'] = $data['general_wallet_txnNo'];
            $log_wallet_transaction['TxnIndicator'] = 'CR';
            $log_wallet_transaction['partnerRefNo'] = $partnerRefNo;
            $this->ci->logging_model->logWalletTransaction($log_wallet_transaction);
            return $response;
		}
	}

function debitGeneralWallet($data)																					//7
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
		$soap_do = curl_init();
		curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($soap_do, CURLOPT_POST,true);            
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
		<SOAP-ENV:Body>
			<sas:TransactionRequest>
				<SessionID>".SessionID."</SessionID>
				<ProductCode>".PRODUCT_CODE."</ProductCode>
				<TxnIdentifierType>PAR</TxnIdentifierType>
				<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
				<Amount>".$data['trans_general_wallet_amount']."</Amount>
				<Currency>356</Currency>
				<Narration></Narration>
				<WalletCode>SMP924</WalletCode>
				<TxnNo>".$data['general_wallet_txnNo']."</TxnNo>
				<CardType>N</CardType>
				<TxnIndicator>DR</TxnIndicator>
				<OTP></OTP>
				<SMSFlag>N</SMSFlag>
				<Filler1></Filler1>
				<Filler2></Filler2>
				<Filler3></Filler3>
				<Filler4></Filler4>
				<Filler5></Filler5>
			</sas:TransactionRequest>
		</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>");
		$response = curl_exec($soap_do);
		$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:TransactionRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
            $log_wallet_transaction['ackNo'] = $response ['AckNo'];
            $log_wallet_transaction['ResponseCode'] = $response ['ResponseCode'];
            $log_wallet_transaction['ResponseMessage'] = $response ['ResponseMessage'];
            $log_wallet_transaction['wallet_type'] = 'GENERAL';
            $log_wallet_transaction['wallet_code'] = 'SMP924';
            $log_wallet_transaction['amount'] = number_format(($data['trans_general_wallet_amount']/100),2,'.','');
            $log_wallet_transaction['txnNo'] = $data['general_wallet_txnNo'];
            $log_wallet_transaction['TxnIndicator'] = 'DR';
            $log_wallet_transaction['partnerRefNo'] = $partnerRefNo;
            $this->ci->logging_model->logWalletTransaction($log_wallet_transaction);
		return $response;
		}
	}
	
function creditVoucherWallet($data)																					//7
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
            $data['expiry_date'] = $this->ci->paymentgateway->expiryDateToNumberOfDays($data['expiry_date']);
            $soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:TransactionRequest>
					<SessionID>".SessionID."</SessionID>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<TxnIdentifierType>PAR</TxnIdentifierType>
					<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
					<Amount>".$data['trans_voucher_wallet_amount']."</Amount>
					<Currency>356</Currency>
					<Narration></Narration>
					<WalletCode>SGP924</WalletCode>
					<TxnNo>".$data['voucher_wallet_txnNo']."</TxnNo>
					<CardType>N</CardType>
					<TxnIndicator>CR</TxnIndicator>
					<OTP></OTP>
					<SMSFlag>N</SMSFlag>
					<Filler1>".$data['expiry_date']."</Filler1>
					<Filler2>".$data['voucher_type']."</Filler2>
					<Filler3>".$data['voucher_id']."</Filler3>
					<Filler4></Filler4>
					<Filler5></Filler5>
				</sas:TransactionRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:TransactionRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
			
            $log_wallet_transaction['ackNo'] = $response ['AckNo'];
            $log_wallet_transaction['ResponseCode'] = $response ['ResponseCode'];
            $log_wallet_transaction['ResponseMessage'] = $response ['ResponseMessage'];
            $log_wallet_transaction['wallet_type'] = 'VOUCHER';
            $log_wallet_transaction['wallet_code'] = 'SGP924';
            $log_wallet_transaction['amount'] = number_format(($data['trans_voucher_wallet_amount']/100),2,'.','');
            $log_wallet_transaction['txnNo'] = $data['voucher_wallet_txnNo'];
            $log_wallet_transaction['TxnIndicator'] = 'CR';
            $log_wallet_transaction['partnerRefNo'] = $partnerRefNo;
            $log_wallet_transaction['voucher_id'] = $data['voucher_id'];
            $log_wallet_transaction['voucher_expiry'] = $data['expiry_date'];
            $this->ci->logging_model->logWalletTransaction($log_wallet_transaction);
			return $response;
		}
		
	}
function debitVoucherWallet($data)																					//7
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
			{
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:TransactionRequest>
					<SessionID>".SessionID."</SessionID>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<TxnIdentifierType>PAR</TxnIdentifierType>
					<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
					<Amount>".$data['trans_voucher_wallet_amount']."</Amount>
					<Currency>356</Currency>
					<Narration></Narration>
					<WalletCode>SGP924</WalletCode>
					<TxnNo>".$data['voucher_wallet_txnNo']."</TxnNo>
					<CardType>N</CardType>
					<TxnIndicator>DR</TxnIndicator>
					<OTP></OTP>
					<SMSFlag>N</SMSFlag>
					<Filler1></Filler1>
					<Filler2></Filler2>
					<Filler3></Filler3>
					<Filler4></Filler4>
					<Filler5></Filler5>
				</sas:TransactionRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:TransactionRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
                $log_wallet_transaction['ackNo'] = $response ['AckNo'];
                $log_wallet_transaction['ResponseCode'] = $response ['ResponseCode'];
                $log_wallet_transaction['ResponseMessage'] = $response ['ResponseMessage'];
                $log_wallet_transaction['wallet_type'] = 'VOUCHER';
                $log_wallet_transaction['wallet_code'] = 'SGP924';
                $log_wallet_transaction['amount'] = number_format(($data['trans_voucher_wallet_amount']/100),2,'.','');
                $log_wallet_transaction['txnNo'] = $data['voucher_wallet_txnNo'];
                $log_wallet_transaction['TxnIndicator'] = 'DR';
                $log_wallet_transaction['partnerRefNo'] = $partnerRefNo;
                $this->ci->logging_model->logWalletTransaction($log_wallet_transaction);
			return $response;
		}
	}
	function queryTransactionReq()																					//8
	{
		$soap_do = curl_init();
		curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
		curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($soap_do, CURLOPT_POST,true);            
		curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
		<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
		<SOAP-ENV:Body>
			<sas:QueryTransactionRequest>
				<SessionID>".SessionID."</SessionID>
				<QueryReqNo>71319587</QueryReqNo>
				<TxnNo>818017969490</TxnNo>
				<Filler1></Filler1>
				<Filler2></Filler2>
				<Filler3></Filler3>
				<Filler4></Filler4>
				<Filler5></Filler5>
			</sas:QueryTransactionRequest>
		</SOAP-ENV:Body>
		</SOAP-ENV:Envelope>");
		$response = curl_exec($soap_do);
		$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:QueryTransactionRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
	
	function generalWalletBal($data)																					//9
	{
//	$key = true;
//		while($key){
			if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
			{
				$soap_do = curl_init();
				curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
				curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($soap_do, CURLOPT_POST,true);            
				curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
				<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:ns1='http://uat-api.shmart.in/services/simulator'>
				<SOAP-ENV:Body>
					<ns1:BalanceEnquiryRequest>
						<SessionID>".SessionID."</SessionID>
						<TxnIdentifierType>PAR</TxnIdentifierType>
						<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
						<WalletCode>SMP924</WalletCode>
						<ProductCode>".PRODUCT_CODE."</ProductCode>
						<SMSFlag>N</SMSFlag>
					</ns1:BalanceEnquiryRequest>
				</SOAP-ENV:Body>
				</SOAP-ENV:Envelope>");
				$response = curl_exec($soap_do);
				$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
				$ns = $xml->getNamespaces(true);
				$soap = $xml->children($ns['SOAP-ENV']);
				$res = $soap->Body->children($ns['ns1']);
				foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:BalanceEnquiryRequestResponse/return') as $item) 
				{
				  $response =  (array) $item;
				}
//				if($response['ResponseCode'] == '0') $key = false;
//			}
		    }
        $response['wallet_type_balance'] = 'GENERAL';
        $this->ci->logging_model->logWalletBalance($response);
		$response['AvailableBalance'] = ($response['AvailableBalance'] / 100);
        return $response;
	}
	function voucherWalletBal($data)																					//9
	{
//	$key = true;
//		while($key){
			if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
			{
                $soap_do = curl_init();
                curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
                curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
                curl_setopt($soap_do, CURLOPT_POST,true);
                curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
                <SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:ns1='http://uat-api.shmart.in/services/simulator'>
                <SOAP-ENV:Body>
                    <ns1:BalanceEnquiryRequest>
                        <SessionID>".SessionID."</SessionID>
                        <TxnIdentifierType>PAR</TxnIdentifierType>
                        <MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
                        <WalletCode>SGP924</WalletCode>
                        <ProductCode>".PRODUCT_CODE."</ProductCode>
                        <SMSFlag>N</SMSFlag>
                    </ns1:BalanceEnquiryRequest>
                </SOAP-ENV:Body>
                </SOAP-ENV:Envelope>");
                $response = curl_exec($soap_do);
                $xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
                $ns = $xml->getNamespaces(true);
                $soap = $xml->children($ns['SOAP-ENV']);
                $res = $soap->Body->children($ns['ns1']);
                foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:BalanceEnquiryRequestResponse/return') as $item)
                {
                  $response =  (array) $item;
                }
    //		        if($response['ResponseCode'] == '0') $key = false;
    //			}
		    }
        $response['wallet_type_balance'] = 'VOUCHER';
        $this->ci->logging_model->logWalletBalance($response);
		$response['AvailableBalance'] = ($response['AvailableBalance'] / 100);
        return $response;

	}
	function totalWalletBal($data)																					//9
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:ns1='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<ns1:BalanceEnquiryRequest>
					<SessionID>".SessionID."</SessionID>
					<TxnIdentifierType>PAR</TxnIdentifierType>
					<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
					<WalletCode></WalletCode>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<SMSFlag>N</SMSFlag>
				</ns1:BalanceEnquiryRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:BalanceEnquiryRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $response['wallet_type_balance'] = 'TOTAL';
            $this->ci->logging_model->logWalletBalance($response);
            return $response;
		} 
	}
	function accountBlockReq($data)																					//10
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:ns1='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<ns1:AccountBlockRequest>
					<SessionID>".SessionID."</SessionID>
					<TxnIdentifierType>PAR</TxnIdentifierType>
					<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<SMSFlag>N</SMSFlag>
				</ns1:AccountBlockRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:AccountBlockRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $log_wallet_account_action['AccountActionStatus'] = $response ['AccountBlockStatus'];
            $log_wallet_account_action['AccountBlockDateTime'] = $response ['AccountBlockDateTime'];
            $log_wallet_account_action['ResponseCode'] = $response ['ResponseCode'];
            $log_wallet_account_action['ResponseMessage'] = $response ['ResponseMessage'];
            $log_wallet_account_action['accountAction'] = 'BLOCK';
            $log_wallet_account_action['partnerRefNo'] = $partnerRefNo;
            $this->ci->logging_model->logWalletAccountAction($log_wallet_account_action);
			return $response;
		}
	}
	
	function accountUnblockReq($data)																					//11
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:ns1='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<ns1:AccountUnBlockRequest>
					<SessionID>".SessionID."</SessionID>
					<TxnIdentifierType>PAR</TxnIdentifierType>
					<MemberIDCardNo>".$partnerRefNo."</MemberIDCardNo>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<SMSFlag>N</SMSFlag>
				</ns1:AccountUnBlockRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:AccountUnBlockRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $log_wallet_account_action['AccountActionStatus'] = $response ['AccountBlockStatus'];
            $log_wallet_account_action['AccountBlockDateTime'] = date('Y-m-d H:i:s');
            $log_wallet_account_action['ResponseCode'] = $response ['ResponseCode'];
            $log_wallet_account_action['ResponseMessage'] = $response ['ResponseMessage'];
            $log_wallet_account_action['accountAction'] = 'UNBLOCK';
            $log_wallet_account_action['partnerRefNo'] = $partnerRefNo;
            $this->ci->logging_model->logWalletAccountAction($log_wallet_account_action);
			return $response;
		}
	}
	
	function beneficiaryRegistrationReq($data)																					//12
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$txnNo=$this->generateWalletReferenceID();
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:BeneficiaryRegistrationRequest>
						<SessionID>".SessionID."</SessionID>
						<TransactionRefNo>".$txnNo."</TransactionRefNo>
						<ProductCode>".PRODUCT_CODE."</ProductCode>
						<RemitterFlag>P</RemitterFlag>
						<RemitterCode>".$partnerRefNo."</RemitterCode>
						<Name>".$data['name']."</Name>
						<Mobile>".$data['bMobileNo']."</Mobile>
						<AddressLine1>".$data['addr1']."</AddressLine1>
						<AddressLine2></AddressLine2>
						<BankIfscode>".$data['ifsc_code']."</BankIfscode>
						<BankAccountNumber>".$data['accountNo']."</BankAccountNumber>
						<OTP></OTP>
						<SMSFlag>N</SMSFlag>
						<Filler1></Filler1>
						<Filler2></Filler2>
						<Filler3></Filler3>
						<Filler4></Filler4>
						<Filler5></Filler5>
				</sas:BeneficiaryRegistrationRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:BeneficiaryRegistrationRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $log_wallet_ben_reg_req['partnerRefNo'] = $partnerRefNo;
            $log_wallet_ben_reg_req['TransactionRefNo'] = $txnNo;
            $log_wallet_ben_reg_req['Name'] = $data['name'];
            $log_wallet_ben_reg_req['Mobile'] = $data['bMobileNo'];
            $log_wallet_ben_reg_req['BankIfscCode'] = $data['ifsc_code'];
            $log_wallet_ben_reg_req['BankAccountNumber'] = $data['accountNo'];
            $log_wallet_ben_reg_req['ResponseCode'] = $response['ResponseCode'];
            $log_wallet_ben_reg_req['ResponseMessage'] = $response['ResponseMessage'];
            $this->ci->logging_model->logWalletBeneficiaryReq($log_wallet_ben_reg_req);
			return $response;
		}
	}
	function queryBeneficiary()																					//13
	{
		$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:QueryBeneficiaryRequest>
					<SessionID>".SessionID."</SessionID>
					<QueryReqNo>36382156</QueryReqNo>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<RemitterFlag>P</RemitterFlag>
					<RemitterCode>12341234</RemitterCode>
					<BeneficiaryCode>2469647</BeneficiaryCode>
					<Filler1></Filler1>
					<Filler2></Filler2>
					<Filler3></Filler3>
					<Filler4></Filler4>
					<Filler5></Filler5>
				</sas:QueryBeneficiaryRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:QueryBeneficiaryRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
	
	function queryBeneficiaryList($data)																					//14
	{
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$soap_do = curl_init();
				curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
				curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
				curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
				curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
				curl_setopt($soap_do, CURLOPT_POST,true);            
				curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
				<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
				<SOAP-ENV:Body>
				<sas:QueryBeneficiaryListRequest>
				  <SessionID>".SessionID."</SessionID>
				  <QueryReqNo>36382156</QueryReqNo>
				  <ProductCode>".PRODUCT_CODE."</ProductCode>
				  <RemitterFlag>P</RemitterFlag>
				  <RemitterCode>".$partnerRefNo."</RemitterCode>
				  <Filler1></Filler1>
				  <Filler2></Filler2>
				  <Filler3></Filler3>
				  <Filler4></Filler4>
				  <Filler5></Filler5>
				</sas:QueryBeneficiaryListRequest>
				</SOAP-ENV:Body>
				</SOAP-ENV:Envelope>");
				$response = curl_exec($soap_do);
				$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
				$ns = $xml->getNamespaces(true);
				$soap = $xml->children($ns['SOAP-ENV']);
				$res = $soap->Body->children($ns['ns1']);
				foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:QueryBeneficiaryListRequestResponse/return') as $item) 
				{
				  $response =  (array) $item;
				}
				return $response;
		}
	}
	function deactivateBeneficiaryReq($data)																					//15
	{
        $data['BeneficiaryCode'] = '32539378';
        if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
        {
            $txnNo=$this->generateWalletReferenceID();
            $soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:DeactivateBeneficiaryRequest>
				  <SessionID>".SessionID."</SessionID>
				  <TransactionRefNo>".$txnNo."</TransactionRefNo>
				  <ProductCode>".PRODUCT_CODE."</ProductCode>
				  <RemitterFlag>P</RemitterFlag>
				  <RemitterCode>".$partnerRefNo."</RemitterCode>
				  <BeneficiaryCode>".$data['BeneficiaryCode']."</BeneficiaryCode>
				  <SMSFlag>N</SMSFlag>
				  <Filler1></Filler1>
				  <Filler2></Filler2>
				  <Filler3></Filler3>
				  <Filler4></Filler4>
				  <Filler5></Filler5>
				</sas:DeactivateBeneficiaryRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:DeactivateBeneficiaryRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
        $log_wallet_ben_deactivate_req['partnerRefNo'] = $partnerRefNo;
        $log_wallet_ben_deactivate_req['TransactionRefNo'] = $txnNo;
        $log_wallet_ben_deactivate_req['BeneficiaryCode'] = $data['BeneficiaryCode'];
        $log_wallet_ben_deactivate_req['ResponseCode'] = $response['ResponseCode'];
        $log_wallet_ben_deactivate_req['ResponseMessage'] = $response['ResponseMessage'];
        $log_wallet_ben_deactivate_req['ackNo'] = $response['AckNo'];
        $this->ci->logging_model->logWalletBeneficiaryDeactivate($log_wallet_ben_deactivate_req);
		return $response;
        }
	}
	function remittanceTransactionReq($data)																					//16
	{
        $data['BeneficiaryCode'] = '49642171';
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
			$txnNo=$this->generateWalletReferenceID();
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:RemittanceTransactionRequest>
					<SessionID>".SessionID."</SessionID>
					<TransactionRefNo>".$txnNo."</TransactionRefNo>
					<ProductCode>".PRODUCT_CODE."</ProductCode>
					<WalletCode>SMP924</WalletCode>
					<RemitterFlag>P</RemitterFlag>
					<RemitterCode>".$partnerRefNo."</RemitterCode>
					<BeneficiaryCode>5432112345</BeneficiaryCode>
					<Amount>".$data['amount']."</Amount>
					<Narration>Remittance No. 123457</Narration>
					<RemittanceType>NEFT</RemittanceType>
					<OTP></OTP>
					<SMSFlag>N</SMSFlag>
					<Filler1></Filler1>
					<Filler2></Filler2>
					<Filler3></Filler3>
					<Filler4></Filler4>
					<Filler5></Filler5>
				</sas:RemittanceTransactionRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:RemittanceTransactionRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $log_wallet_remittance_trans_req['partnerRefNo'] = $partnerRefNo;
            $log_wallet_remittance_trans_req['TransactionRefNo'] = $txnNo;
            $log_wallet_remittance_trans_req['amount'] = ($data['amount']/100);
            $log_wallet_remittance_trans_req['BeneficiaryCode'] = $data['BeneficiaryCode'];
            $log_wallet_remittance_trans_req['ResponseCode'] = $response['ResponseCode'];
            $log_wallet_remittance_trans_req['ResponseMessage'] = $response['ResponseMessage'];
            $log_wallet_remittance_trans_req['ackNo'] = $response['AckNo'];
            $this->ci->logging_model->logWalletRemittanceTransReq($log_wallet_remittance_trans_req);
			return $response;
		}
	}
	function queryRemittanceReq()																					//17
	{
		$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:QueryRemittanceRequest>
				  <SessionID>".SessionID."</SessionID>
				  <QueryReqNo>18767963</QueryReqNo>
				  <TransactionRefNo>569763597941</TransactionRefNo>
				  <Filler1></Filler1>
				  <Filler2></Filler2>
				  <Filler3></Filler3>
				  <Filler4></Filler4>
				  <Filler5></Filler5>
				</sas:QueryRemittanceRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:QueryRemittanceRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
	function walletTransferReq($data)																					//18
	{
		$data['wallet_code']='SMP924';
		if($partnerRefNo = $this->ci->consumer_model->getPartnerRefNo($data))
		{
		$txnNo=$this->generateWalletReferenceID();
			$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:WalletTransferRequest>
				<SessionID>".SessionID."</SessionID> 
				<TransactionRefNo>".$txnNo."</TransactionRefNo>
				<ProductCode>".PRODUCT_CODE."</ProductCode> 
				<RemitterWalletCode>".$data['wallet_code']."</RemitterWalletCode>
				<RemitterFlag>P</RemitterFlag> 
				<RemitterCode>".$partnerRefNo."</RemitterCode> 
				<BeneficiaryEmail>".$data['friendEmail']."</BeneficiaryEmail>
				<BeneficiaryMobile>".$data['friendMobileNo']."</BeneficiaryMobile> 
				<BeneficiaryWalletCode></BeneficiaryWalletCode>
				<Narration>".$data['narration']."</Narration> 
				<Amount>".$data['amount']."</Amount> 
				<SMSFlag>Y</SMSFlag >
				<Filler1></Filler1> 
				<Filler2></Filler2> 
				<Filler3></Filler3> 
				<Filler4></Filler4> 
				<Filler5></Filler5> 
				</sas:WalletTransferRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
			$ns = $xml->getNamespaces(true);
			$soap = $xml->children($ns['SOAP-ENV']);
			$res = $soap->Body->children($ns['ns1']);
			foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:WalletTransferRequestResponse/return') as $item) 
			{
			  $response =  (array) $item;
			}
            $log_wallet_wallet_req['partnerRefNo'] = $partnerRefNo;
            $log_wallet_wallet_req['TransactionRefNo'] = $txnNo;
            $log_wallet_wallet_req['walletCode'] = $data['wallet_code'];
            $log_wallet_wallet_req['bMobileNo'] = $data['friendMobileNo'];
            $log_wallet_wallet_req['bEmail'] = $data['friendEmail'];
            $log_wallet_wallet_req['narration'] = $data['narration'];
            $log_wallet_wallet_req['amount'] = ($data['amount']/100);
            $log_wallet_wallet_req['ResponseCode'] = $response['ResponseCode'];
            $log_wallet_wallet_req['ResponseMessage'] = $response['ResponseMessage'];
            $log_wallet_wallet_req['ackNo'] = $response['AckNo'];
            $this->ci->logging_model->logWalletToWalletTransReq($log_wallet_wallet_req);
			
			return $response;
		}
	}
	function queryTransferReq()																					//19
	{
		$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:QueryTransferRequest>
				<SessionID>".SessionID."</SessionID>
				<QueryReqNo>123456789101</QueryReqNo>
				<TransactionRefNo>1234567891012347</TransactionRefNo>
				<ProductCode>".PRODUCT_CODE."</ProductCode>
				<RemitterFlag>P</RemitterFlag>
				<RemitterCode>12345678</RemitterCode>
				<Filler1></Filler1>
				<Filler2></Filler2>
				<Filler3></Filler3>
				<Filler4></Filler4>
				<Filler5></Filler5>
				</sas:QueryTransferRequest>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:QueryTransferRequestResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
	function logoff()																					//20
	{
		$soap_do = curl_init();
			curl_setopt($soap_do, CURLOPT_URL,API_ENDPOINT);
			curl_setopt($soap_do, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST,false);
			curl_setopt($soap_do, CURLOPT_POST,true);            
			curl_setopt($soap_do, CURLOPT_POSTFIELDS,"<?xml version='1.1'?>
			<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:sas='http://uat-api.shmart.in/services/simulator'>
			<SOAP-ENV:Body>
				<sas:Logoff>
				<SessionID>".SessionID."</SessionID>
				</sas:Logoff>
			</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>");
			$response = curl_exec($soap_do);
			$xml = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
		$ns = $xml->getNamespaces(true);
		$soap = $xml->children($ns['SOAP-ENV']);
		$res = $soap->Body->children($ns['ns1']);
		foreach ($xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ns1:LogoffResponse/return') as $item) 
		{
		  $response =  (array) $item;
		}
		return $response;
	}
}