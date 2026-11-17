<?php

// Local vector illustrations. No remote image dependencies or frontend build step.
$path = dirname(__DIR__).'/public/images/';
$head = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1400 800"><defs><linearGradient id="sky" x2="0" y2="1"><stop stop-color="#89d5fb"/><stop offset="1" stop-color="#e4f8ff"/></linearGradient><linearGradient id="water" x2="0" y2="1"><stop stop-color="#69d5e6"/><stop offset="1" stop-color="#1b9ed0"/></linearGradient><linearGradient id="land" x2="0" y2="1"><stop stop-color="#83c83f"/><stop offset="1" stop-color="#248f54"/></linearGradient><g id="tree"><path d="M0 0 2-108M1-49-30-80M2-65 35-98" stroke="#796138" stroke-width="10" fill="none"/><g fill="#2a9b53"><circle cx="-28" cy="-102" r="30"/><circle cx="15" cy="-122" r="38"/><circle cx="46" cy="-103" r="26"/></g><g fill="#64be39"><circle cx="-17" cy="-125" r="24"/><circle cx="25" cy="-142" r="22"/><circle cx="49" cy="-118" r="21"/></g></g><g id="grass"><path d="M0 0Q-36-40-23-75Q-2-53 3-12Q1-74 27-94Q30-42 8 0Q38-58 61-42Q29-20 12 4" fill="#49ac3e"/><path d="M0 0Q-39-17-48-48Q-16-47 5-3M9 0Q28-30 43-22" fill="#85c93e"/></g><g id="lily"><ellipse rx="34" ry="10" fill="#58b82d"/><path d="M0 0-7-11 17-5Z" fill="#2a9736"/><path d="M0-4Q-27-6-22-22Q-4-23 0-4M0-4Q24-7 22-22Q8-23 0-4" fill="#f39cc8"/><path d="M0-5Q-15-27 0-36Q16-23 0-5" fill="#f8c0dc"/><path d="M-4-8 0-17 5-8" fill="#ffda53"/></g><g id="bird"><path d="M-22 5Q-5-14 0 0Q12-17 30-10" stroke="#304f4f" stroke-width="4" fill="none" stroke-linecap="round"/></g></defs>';
foreach (['wetland', 'rawa-lebak', 'rawa-pasang-surut', 'rawa-gambut', 'hutan-mangrove', 'sungai'] as $kind) {
    $svg = $head.'<path fill="url(#sky)" d="M0 0H1400V800H0Z"/><circle cx="1090" cy="105" r="60" fill="#fff6bf" opacity=".8"/>';
    foreach ([[150, 100], [450, 60], [780, 140], [1220, 170]] as [$x,$y]) {
        $svg .= '<g fill="#fff" opacity=".7" transform="translate('.$x.' '.$y.')"><ellipse rx="90" ry="20"/><circle cy="-15" r="28"/><circle cx="-35" cy="-9" r="22"/></g>';
    }
    $svg .= '<path d="M0 370Q130 270 260 330Q400 230 570 315Q760 210 930 305Q1120 220 1400 310V600H0" fill="#6fbbbc" opacity=".6"/><path d="M0 430Q230 300 400 370Q570 300 720 350Q950 265 1110 365Q1280 300 1400 340V800H0" fill="#3f9d75"/><path d="M0 440Q280 375 500 425Q780 375 1400 410V800H0Z" fill="url(#water)"/>';
    if ($kind === 'sungai') {
        $svg .= '<path d="M500 380Q820 410 640 490Q460 570 940 800H1400V390Z" fill="url(#land)"/>';
    } elseif ($kind === 'rawa-gambut') {
        $svg .= '<path d="M0 450Q600 360 1400 450V800H0" fill="#7b7660" opacity=".55"/>';
    } else {
        $svg .= '<path d="M0 410Q100 420 240 480Q360 525 210 570L0 620Z" fill="url(#land)"/><path d="M1400 410Q1130 415 980 480Q1060 520 1400 560" fill="url(#land)"/>';
    }
    for ($i = 0; $i < 25; $i++) {
        $x = $i * 62 - 30;
        $y = 410 + (($i * 13) % 45);
        $scale = .5 + (($i * 7) % 6) / 10;
        $svg .= '<use href="#tree" transform="translate('.$x.' '.$y.') scale('.$scale.')"/>';
    }
    for ($i = 0; $i < 17; $i++) {
        $x = 80 + (($i * 83) % 1250);
        $y = 505 + (($i * 29) % 205);
        $svg .= '<path d="M'.$x.' '.$y.'h'.(30 + $i * 3).'" stroke="#c8f9f3" stroke-width="3" opacity=".6" stroke-linecap="round"/>';
    }
    if ($kind === 'hutan-mangrove') {
        for ($i = 0; $i < 8; $i++) {
            $x = 90 + $i * 175;
            $svg .= '<g transform="translate('.$x.' 560)"><path d="M0-70-35 15M0-70 35 15M0-50-10 30M0-50 18 30" stroke="#795f35" stroke-width="9" fill="none"/><use href="#tree" transform="translate(0 -40) scale(1.3)"/></g>';
        }
    }
    if ($kind === 'wetland' || $kind === 'rawa-pasang-surut') {
        $svg .= '<g transform="translate(1050 395)"><path d="m40 0-18 185M108 0l20 185M28 180 115 40M35 70 122 177" stroke="#547d6a" stroke-width="9" fill="none"/><path d="M15-15h115v50H15Z" fill="#5a9c91"/><path d="M0-20 75-75 147-20Z" fill="#286b72"/><path d="M15-15h115M15 20h115M25-15v40M75-15v40M120-15v40" stroke="#edda95" stroke-width="5"/><path d="M72-75v-35" stroke="#5d714b" stroke-width="5"/></g><g transform="translate(1060 540)"><path d="M0 10v155M235 10v155M40 20v155M185 20v155" stroke="#806940" stroke-width="10"/><path d="M-5-65h250V65H-5Z" fill="#ddbe78"/><path d="M-38-60 120-148 280-60Z" fill="#fbefd0" stroke="#bd9f68" stroke-width="5"/><path d="M80-35h45V65H80Z" fill="#386d6c"/><path d="M170-25h38v42h-38Z" fill="#276775"/><path d="M-28 70h300M-28 47h300M-20 44v40M40 44v40M150 44v40M255 44v40" stroke="#8d774d" stroke-width="6"/></g>';
    }
    foreach ([[790, 165, 1], [920, 210, .6], [1160, 90, .8]] as [$x,$y,$s]) {
        $svg .= '<use href="#bird" transform="translate('.$x.' '.$y.') scale('.$s.')"/>';
    }
    foreach ([[420, 670], [720, 710], [900, 660], [580, 750], [1030, 740]] as [$x,$y]) {
        $svg .= '<use href="#lily" transform="translate('.$x.' '.$y.')"/>';
    }
    $svg .= '<path d="M0 730Q160 685 300 760Q460 745 600 800H0ZM1400 690Q1250 700 1140 800H1400Z" fill="#258b4c"/>';
    for ($i = 0; $i < 18; $i++) {
        $x = ($i < 9 ? $i * 39 : 1110 + ($i - 9) * 39);
        $y = 785 + (($i * 13) % 25);
        $svg .= '<use href="#grass" transform="translate('.$x.' '.$y.') scale('.(.8 + ($i % 3) * .3).')"/>';
    }
    if ($kind === 'wetland') {
        $svg .= '<g transform="translate(125 475)"><path d="M-65 90Q-23 30-8-50Q30-80 50-25Q40 60 0 85Z" fill="#183c42"/><path d="M0 60Q24 12 14-26Q42-38 43-10Q39 40 0 60Z" fill="#fff5c7"/><ellipse cx="29" cy="-48" rx="32" ry="27" fill="#183c42"/><path d="M42-47Q103-44 109-26L47-31Z" fill="#f1b623"/><path d="M8-65Q31-100 59-59Z" fill="#eab021"/><circle cx="40" cy="-54" r="5" fill="#fff"/><circle cx="41" cy="-54" r="2.5" fill="#152f31"/><path d="m-8 85-7 20M10 80l10 22M-40 104h85" stroke="#8c743a" stroke-width="6"/></g>';
    }
    file_put_contents($path.$kind.'.svg', $svg.'</svg>');
}
$species = [
    'ikan-haruan' => ['fish', '#687b49', 'Haruan'], 'ikan-papuyu' => ['fish', '#9a9862', 'Papuyu'], 'ikan-sepat-rawa' => ['fish', '#7da3ad', 'Sepat Rawa'],
    'bakau' => ['tree', '#2d9853', 'Bakau'], 'nipah' => ['palm', '#359b51', 'Nipah'], 'purun-tikus' => ['reeds', '#4c9e3d', 'Purun Tikus'], 'bekantan' => ['monkey', '#ad6840', 'Bekantan'],
];
foreach ($species as $slug => [$type,$color,$name]) {
    $s = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 400"><defs><linearGradient id="bg" x2="0" y2="1"><stop stop-color="#d9f3de"/><stop offset="1" stop-color="#a4dfdf"/></linearGradient></defs><path fill="url(#bg)" d="M0 0H600V400H0Z"/><circle cx="510" cy="70" r="42" fill="#f7ffd7"/><path d="M0 310Q150 270 300 320T600 300V400H0Z" fill="#61b7b2" opacity=".4"/>';
    if ($type === 'fish') {
        $slim = $slug === 'ikan-haruan';
        $ry = $slim ? 52 : 85;
        $s .= '<g transform="translate(310 215)"><path d="M-130 0-225-80-210 0-225 80Z" fill="'.$color.'" stroke="#3f6152" stroke-width="4"/><path d="M-120-35Q-60-130 90-55M-95 40Q-30 105 65 45" fill="'.$color.'" stroke="#3f6152" stroke-width="4"/><ellipse rx="175" ry="'.$ry.'" fill="'.$color.'" stroke="#3f6152" stroke-width="4"/><path d="M-140 15Q-20 85 140 15Q125 72-20 55Z" fill="#d4d5a0" opacity=".7"/><path d="M65-38Q40 0 72 40" fill="none" stroke="#3e6150" stroke-width="4"/><circle cx="127" cy="-16" r="13" fill="#f9e8a6"/><circle cx="130" cy="-16" r="7" fill="#1e3d36"/><path d="m168 9-27 5" stroke="#3e6150" stroke-width="3"/>';
        if ($slug === 'ikan-sepat-rawa') {
            $s .= '<circle cx="-20" r="15" fill="#395d60"/><circle cx="-120" r="10" fill="#395d60"/><path d="M60 52Q40 115-75 130M45 52Q25 110-100 120" fill="none" stroke="#3e6150" stroke-width="3"/>';
        } else {
            for ($i = 0; $i < 5; $i++) {
                $s .= '<path d="M'.(-110 + $i * 30).' -30q-15 24 0 48" stroke="#344f42" stroke-width="4" fill="none" opacity=".35"/>';
            }
        }
        $s .= '</g>';
    } elseif ($type === 'monkey') {
        $s .= '<path d="M130 310Q270 270 560 295" stroke="#84613d" stroke-width="18" fill="none"/><path d="M335 270Q180 180 185 310Q185 370 120 335" fill="none" stroke="#bb804d" stroke-width="20" stroke-linecap="round"/><ellipse cx="330" cy="230" rx="70" ry="90" fill="#b27548"/><ellipse cx="345" cy="245" rx="44" ry="65" fill="#e5cf9d"/><path d="m290 250-50 55m105-18 35 21m-80-110-40 55" stroke="#b27548" stroke-width="24" stroke-linecap="round" fill="none"/><circle cx="338" cy="130" r="55" fill="#b27548"/><ellipse cx="355" cy="137" rx="36" ry="40" fill="#ead2ad"/><path d="M350 130Q388 121 389 167Q363 181 350 130" fill="#d5a17e"/><circle cx="345" cy="125" r="5" fill="#303d31"/><circle cx="374" cy="123" r="5" fill="#303d31"/>';
    } elseif ($type === 'tree') {
        $s .= '<path d="M300 170v120m0-45-90 100m90-100 100 100m-100-90-30 100m30-100 35 100" stroke="#8a704c" stroke-width="13" fill="none"/><g fill="'.$color.'"><circle cx="235" cy="155" r="65"/><circle cx="330" cy="140" r="78"/><circle cx="375" cy="180" r="55"/></g><g fill="#69bb4d"><circle cx="285" cy="100" r="52"/><circle cx="210" cy="125" r="36"/></g>';
    } elseif ($type === 'palm') {
        for ($i = 0; $i < 7; $i++) {
            $x = 100 + $i * 65;
            $s .= '<path d="M300 340Q'.($x - 40).' 180 '.$x.' 80" stroke="#478a3d" stroke-width="5" fill="none"/><path d="M300 330Q'.($x - 80).' 220 '.$x.' 80Q'.($x + 45).' 205 300 330" fill="'.($i % 2 ? '#438f42' : '#68b850').'"/>';
        }
    } else {
        for ($i = 0; $i < 17; $i++) {
            $x = 190 + $i * 15;
            $s .= '<path d="M'.(260 + $i * 5).' 350Q'.($x - 20).' 220 '.$x.' '.(65 + ($i * 17) % 100).'" stroke="'.($i % 2 ? '#3e8c42' : '#69b24c').'" stroke-width="9" fill="none" stroke-linecap="round"/>';
        }
    }
    $s .= '<path d="M30 360h100M450 340h90" stroke="#e1fbef" stroke-width="3"/><text x="25" y="30" font-family="Arial,sans-serif" font-size="12" fill="#3f7c69">ILUSTRASI PEMBELAJARAN</text></svg>';
    file_put_contents($path.$slug.'.svg', $s);
}
echo "13 ilustrasi lokal dibuat.\n";
