<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Seismometer;

class UpdateSeismometer extends Command
{
    protected $seismoters = 
    [
        [
          'code' => 'BAT',
          'station' => 'BTR',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BAT',
          'station' => 'DNU',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BAT',
          'station' => 'SGN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BAT',
          'station' => 'YMP',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BAT',
          'station' => 'KIN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'MAH',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'MHW',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'RIN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'RIN',
          'channel' => 'EHE',
        ],
        [
          'code' => 'LOK',
          'station' => 'RIN',
          'channel' => 'EHN',
        ],
        [
          'code' => 'LOK',
          'station' => 'SOP',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'RUA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'TTW',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'WLN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'WLN',
          'channel' => 'EHN',
        ],
        [
          'code' => 'LOK',
          'station' => 'WLN',
          'channel' => 'EHE',
        ],
        [
          'code' => 'LOK',
          'station' => 'AWU1',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'AWU1',
          'channel' => 'ELZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'AMBL',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LOK',
          'station' => 'AMBL',
          'channel' => 'EHE',
        ],
        [
          'code' => 'LOK',
          'station' => 'EMP',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SOP',
          'station' => 'SLN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SOP',
          'station' => 'WNR',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SOP',
          'station' => 'ASOP',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LEW',
          'station' => 'BGM',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LEW',
          'station' => 'BOL',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LEW',
          'station' => 'BTP',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LEW',
          'station' => 'LWL',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LEW',
          'station' => 'SIR',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LEW',
          'station' => 'WER',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'LEW',
          'station' => 'WWR',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'PSAG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'GTOH',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'TMKS',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'ABNG',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'YHKR',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'YHKR',
          'channel' => 'BHE',
        ],
        [
          'code' => 'AGU',
          'station' => 'YHKR',
          'channel' => 'BHN',
        ],
        [
          'code' => 'AGU',
          'station' => 'CEGI',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'CEGI',
          'channel' => 'BHE',
        ],
        [
          'code' => 'AGU',
          'station' => 'CEGI',
          'channel' => 'BHN',
        ],
        [
          'code' => 'AGU',
          'station' => 'DUKU',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'AGU',
          'station' => 'DUKU',
          'channel' => 'BHE',
        ],
        [
          'code' => 'AGU',
          'station' => 'DUKU',
          'channel' => 'BHN',
        ],
        [
          'code' => 'AGU',
          'station' => 'BATU',
          'channel' => 'BHN',
        ],
        [
          'code' => 'EGO',
          'station' => 'HBGA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'EGO',
          'station' => 'LBLG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'EGO',
          'station' => 'ROTA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'EGO',
          'station' => 'TOBI',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'KOAK',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'LDLR',
          'channel' => 'BHX',
        ],
        [
          'code' => 'RIN',
          'station' => 'LDLR',
          'channel' => 'BHY',
        ],
        [
          'code' => 'RIN',
          'station' => 'PLWG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'TENG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'LDLR',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'TOGK',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'BARI',
          'channel' => 'BHE',
        ],
        [
          'code' => 'RIN',
          'station' => 'BARI',
          'channel' => 'BHN',
        ],
        [
          'code' => 'RIN',
          'station' => 'BARI',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'KLA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIN',
          'station' => 'KLA',
          'channel' => 'EHE',
        ],
        [
          'code' => 'RIN',
          'station' => 'KLA',
          'channel' => 'EHN',
        ],
        [
          'code' => 'BAN',
          'station' => 'GSB',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BAN',
          'station' => 'KLM',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BAN',
          'station' => 'PBS',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BAN',
          'station' => 'PTM',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'TLR',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'KIE',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'GMK',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'IBU',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'DKN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'KLB',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'ARK',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'FORA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GML',
          'station' => 'ARBL',
          'channel' => 'HHE',
        ],
        [
          'code' => 'GML',
          'station' => 'ARBL',
          'channel' => 'HHN',
        ],
        [
          'code' => 'GML',
          'station' => 'ARBL',
          'channel' => 'HHZ',
        ],
        [
          'code' => 'MER',
          'station' => 'PUSS',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'MER',
          'station' => 'DELS',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'MER',
          'station' => 'PASB',
          'channel' => 'HHZ',
        ],
        [
          'code' => 'MER',
          'station' => 'MERB',
          'channel' => 'HHZ',
        ],
        [
          'code' => 'GUN',
          'station' => 'MIS',
          'channel' => 'EHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'GUN',
          'station' => 'LGP',
          'channel' => 'EHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'GUN',
          'station' => 'CTS',
          'channel' => 'EHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'GUN',
          'station' => 'JPT',
          'channel' => 'EHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'GUN',
          'station' => 'SDG',
          'channel' => 'EHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'PAP',
          'station' => 'PPD',
          'channel' => 'EHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'PAP',
          'station' => 'KBY',
          'channel' => 'EHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'PAP',
          'station' => 'MIS',
          'channel' => 'EHE',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'PAP',
          'station' => 'MSGG',
          'channel' => 'BHZ',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'PAP',
          'station' => 'MSGG',
          'channel' => 'BHN',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'PAP',
          'station' => 'MSGG',
          'channel' => 'BHE',
          'network' => 'IG',
          'location' => '00'
        ],
        [
          'code' => 'IYA',
          'station' => 'IYA2',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'IYA',
          'station' => 'IYA3',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KLM',
          'station' => 'KLMT',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KLM',
          'station' => 'ROPA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAM',
          'station' => 'KALO',
          'channel' => 'EHE',
        ],
        [
          'code' => 'TAM',
          'station' => 'PCSL',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAM',
          'station' => 'KALO',
          'channel' => 'EHN',
        ],
        [
          'code' => 'TAM',
          'station' => 'HODO',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAM',
          'station' => 'TEKO',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAM',
          'station' => 'KALO',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'GBR',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'SKN',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'MMD',
          'channel' => 'SHE',
        ],
        [
          'code' => 'SIN',
          'station' => 'MMD',
          'channel' => 'SHN',
        ],
        [
          'code' => 'SIN',
          'station' => 'MMD',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'KWR',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'SBY',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'PBK',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'MDG',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'MDG',
          'channel' => 'BHN',
        ],
        [
          'code' => 'SIN',
          'station' => 'MDG',
          'channel' => 'BHE',
        ],
        [
          'code' => 'SIN',
          'station' => 'SKA',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'SKA',
          'channel' => 'BHN',
        ],
        [
          'code' => 'SIN',
          'station' => 'SKA',
          'channel' => 'BHE',
        ],
        [
          'code' => 'SIN',
          'station' => 'KBY',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'SIN',
          'station' => 'SGG',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'RIE',
          'station' => 'LKA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIE',
          'station' => 'RIE',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RIE',
          'station' => 'EBU',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SAN',
          'station' => 'BLDA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SAN',
          'station' => 'SOLA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SAN',
          'station' => 'SURI',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAL',
          'station' => 'GBO',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAL',
          'station' => 'KTH',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'GDEZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'CMEZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'RTUX',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'RTUY',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'RTUZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'TOWZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'PTRZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'CTRZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TPR',
          'station' => 'POSZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'MAR',
          'station' => 'BTPL',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'MAR',
          'station' => 'PACT',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'MAR',
          'station' => 'SABU',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'MAR',
          'station' => 'SABU',
          'channel' => 'EHN',
        ],
        [
          'code' => 'MAR',
          'station' => 'SABU',
          'channel' => 'EHE',
        ],
        [
          'code' => 'MAR',
          'station' => 'POST',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'MAR',
          'station' => 'POST',
          'channel' => 'EHN',
        ],
        [
          'code' => 'TAN',
          'station' => 'TDKT',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAN',
          'station' => 'LASI',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAN',
          'station' => 'LASI',
          'channel' => 'EHN',
        ],
        [
          'code' => 'TAN',
          'station' => 'LASI',
          'channel' => 'EHE',
        ],
        [
          'code' => 'SMR',
          'station' => 'PNCK',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SMR',
          'station' => 'KPLO',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SMR',
          'station' => 'LEKR',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SMR',
          'station' => 'TRTS',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SMR',
          'station' => 'BANG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BRO',
          'station' => 'BRMO',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'BRO',
          'station' => 'LMNG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'ROK',
          'station' => 'ONA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'ROK',
          'station' => 'LIDI',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KAR',
          'station' => 'AKB',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KAR',
          'station' => 'BBL',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KAR',
          'station' => 'KWG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KAR',
          'station' => 'LIA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KAR',
          'station' => 'RUA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'AWU',
          'station' => 'AWU1',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'DUK',
          'station' => 'DKB',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'DUK',
          'station' => 'DKA',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KLD',
          'station' => 'LRNG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KLD',
          'station' => 'SUMB',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GED',
          'station' => 'PUT',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'GED',
          'station' => 'SLK',
          'channel' => 'SHZ',
        ],
        [
          'code' => 'DIE',
          'station' => 'PANG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'DIE',
          'station' => 'PRAH',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'DIE',
          'station' => 'SLAM',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'DIE',
          'station' => 'SLR1',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SUN',
          'station' => 'SUMB',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SUN',
          'station' => 'SUND',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SUN',
          'station' => 'TBG2',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KRA',
          'station' => 'TNJG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KRA',
          'station' => 'SRTG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KRA',
          'station' => 'PULO',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GAL',
          'station' => 'PBTG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GAL',
          'station' => 'KWHG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GAL',
          'station' => 'MLGT',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GAL',
          'station' => 'POSG',
          'channel' => 'BHE',
        ],
        [
          'code' => 'GAL',
          'station' => 'POSG',
          'channel' => 'BHN',
        ],
        [
          'code' => 'GAL',
          'station' => 'POSG',
          'channel' => 'BHZ',
        ],
        [
          'code' => 'GAL',
          'station' => 'PRTS',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'GAL',
          'station' => 'PSML',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KER',
          'station' => 'KRC1',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'KER',
          'station' => 'KRC2',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'IJE',
          'station' => 'IJEN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'RAU',
          'station' => 'MLTN',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SLA',
          'station' => 'GUCI',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SLA',
          'station' => 'GOAZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SLA',
          'station' => 'BBG',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SLA',
          'station' => 'CLK',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SLA',
          'station' => 'BCS',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'SLA',
          'station' => 'JRMZ',
          'channel' => 'EHZ',
        ],
        [
          'code' => 'TAL',
          'station' => 'GBO',
          'channel' => 'EHZ',
        ]
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:seismometer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Udpate data seismometer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Updating Data Seismometer....');

        foreach ($this->seismoters as $item) {
            Seismometer::firstOrCreate(
                [
                    'scnl'=> $item['station'].'_'.$item['channel'].'_VG_00'
                ],
                [
                    'code' => $item['code'],
                    'station' => $item['station'],
                    'channel' => $item['channel'],
                ]
            );
        }

        $this->info('Update Data berhasil');
    }
}
