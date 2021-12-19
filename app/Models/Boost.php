<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boost extends Model
{
    use HasFactory;

    public function status()
    {
        return $this->hasMany(Status::class, 'code', 'status_id');
    }

    protected $fillable = ['user_id', 'orderID', 'platform', 'price', 'data', 'options', 'booster_id', 'status', 'game', 'type_boost'];

    static function ranks(){
        return [
            [
                'min' => -1,
                'max' => 0,
                'text' => 'Unranked',
                'image' => '../../images/ranks/unranked.png',
                'coff' => 0
            ],
            [
                'min' => 0,
                'max' => 300,
                'text' => 'Bronze IV',
                'image' => '../../images/ranks/bronze4.png',
                'coff' => 0.016
            ],
            [
                'min' => 300,
                'max' => 600,
                'text' => 'Bronze III',
                'image' => '../../images/ranks/bronze3.png',
                'coff' => 0.02
            ],
            [
                'min' => 600,
                'max' => 900,
                'text' => 'Bronze II',
                'image' => '../../images/ranks/bronze2.png',
                'coff' => 0.023
            ],
            [
                'min' => 900,
                'max' => 1200,
                'text' => 'Bronze I',
                'image' => '../../images/ranks/bronze1.png',
                'coff' => 0.026
            ],
            [
                'min' => 1200,
                'max' => 1800,
                'text' => 'Silver IV',
                'image' => '../../images/ranks/silver4.png',
                'coff' => 0.0225
            ],
            [
                'min' => 1800,
                'max' => 2000,
                'text' => 'Silver III',
                'image' => '../../images/ranks/silver3.png',
                'coff' => 0.025
            ],
            [
                'min' => 2000,
                'max' => 2400,
                'text' => 'Silver II',
                'image' => '../../images/ranks/silver2.png',
                'coff' => 0.0275
            ],
            [
                'min' => 2400,
                'max' => 2800,
                'text' => 'Silver I',
                'image' => '../../images/ranks/silver1.png',
                'coff' => 0.03
            ],
            [
                'min' => 2800,
                'max' => 3300,
                'text' => 'Gold IV',
                'image' => '../../images/ranks/gold4.png',
                'coff' => 0.03
            ],
            [
                'min' => 3300,
                'max' => 3800,
                'text' => 'Gold III',
                'image' => '../../images/ranks/gold3.png',
                'coff' => 0.034
            ],
            [
                'min' => 3800,
                'max' => 4300,
                'text' => 'Gold II',
                'image' => '../../images/ranks/gold2.png',
                'coff' => 0.036
            ],
            [
                'min' => 4300,
                'max' => 4800,
                'text' => 'Gold I',
                'image' => '../../images/ranks/gold1.png',
                'coff' => 0.04
            ],
            [
                'min' => 4800,
                'max' => 5400,
                'text' => 'Platinum IV',
                'image' => '../../images/ranks/platinum4.png',
                'coff' => 0.05
            ],
            [
                'min' => 5400,
                'max' => 6000,
                'text' => 'Platinum III',
                'image' => '../../images/ranks/platinum3.png',
                'coff' => 0.053
            ],
            [
                'min' => 6000,
                'max' => 6600,
                'text' => 'Platinum II',
                'image' => '../../images/ranks/platinum2.png',
                'coff' => 0.06
            ],
            [
                'min' => 6600,
                'max' => 7200,
                'text' => 'Platinum I',
                'image' => '../../images/ranks/platinum1.png',
                'coff' => 0.066
            ],
            [
                'min' => 7200,
                'max' => 7900,
                'text' => 'Diamond IV',
                'image' => '../../images/ranks/diamond4.png',
                'coff' => 0.1
            ],
            [
                'min' => 7900,
                'max' => 8600,
                'text' => 'Diamond III',
                'image' => '../../images/ranks/diamond3.png',
                'coff' => 0.121
            ],
            [
                'min' => 8600,
                'max' => 9300,
                'text' => 'Diamond II',
                'image' => '../../images/ranks/diamond2.png',
                'coff' => 0.15
            ],
            [
                'min' => 9300,
                'max' => 10000,
                'text' => 'Diamond I',
                'image' => '../../images/ranks/diamond1.png',
                'coff' => 0.171
            ],
            [
                'min' => 10000,
                'max' => 20000,
                'text' => 'Master',
                'image' => '../../images/ranks/master_pro.png',
                'coff' => 0.19
            ]
        ];
    }

    static function ranks_arena(){
        return [
            [
                'min' => 3200,
                'max' => 3600,
                'text' => 'Gold IV',
                'image' => '../../images/ranks/gold4.png',
                'coff' => 0.0625
            ],
            [
                'min' => 3600,
                'max' => 4000,
                'text' => 'Gold III',
                'image' => '../../images/ranks/gold3.png',
                'coff' => 0.075
            ],
            [
                'min' => 4000,
                'max' => 4400,
                'text' => 'Gold II',
                'image' => '../../images/ranks/gold2.png',
                'coff' => 0.0875
            ],
            [
                'min' => 4400,
                'max' => 4800,
                'text' => 'Gold I',
                'image' => '../../images/ranks/gold1.png',
                'coff' => 0.1
            ],
            [
                'min' => 4800,
                'max' => 5200,
                'text' => 'Platinum IV',
                'image' => '../../images/ranks/platinum4.png',
                'coff' => 0.125
            ],
            [
                'min' => 5200,
                'max' => 5600,
                'text' => 'Platinum III',
                'image' => '../../images/ranks/platinum3.png',
                'coff' => 0.15
            ],
            [
                'min' => 5600,
                'max' => 6000,
                'text' => 'Platinum II',
                'image' => '../../images/ranks/platinum2.png',
                'coff' => 0.2
            ],
            [
                'min' => 6000,
                'max' => 6400,
                'text' => 'Platinum I',
                'image' => '../../images/ranks/platinum1.png',
                'coff' => 0.225
            ],
            [
                'min' => 6400,
                'max' => 6800,
                'text' => 'Diamond IV',
                'image' => '../../images/ranks/diamond4.png',
                'coff' => 0.275
            ],
            [
                'min' => 6800,
                'max' => 7200,
                'text' => 'Diamond III',
                'image' => '../../images/ranks/diamond3.png',
                'coff' => 0.35
            ],
            [
                'min' => 7200,
                'max' => 7600,
                'text' => 'Diamond II',
                'image' => '../../images/ranks/diamond2.png',
                'coff' => 0.45
            ],
            [
                'min' => 7600,
                'max' => 8000,
                'text' => 'Diamond I',
                'image' => '../../images/ranks/diamond1.png',
                'coff' => 0.575
            ],
            [
                'min' => 8000,
                'max' => 10000,
                'text' => 'Master',
                'image' => '../../images/ranks/master_pro.png',
                'coff' => 0.575
            ]
        ];
    }

    static function exts(){
        return [
            [
                'title' => 'Duo Queue',
                'name' => 'duo',
                'p' => 75,
                'b' => false
            ],
            [
                'title' => 'Streaming',
                'name' => 'stream',
                'p' => 15,
                'b' => false
            ],
            [
                'title' => 'Priority Completion',
                'name' => 'pt',
                'p' => 20,
                'b' => false
            ],
            [
                'title' => 'Play Offline',
                'name' => 'offline',
                'p' => 0,
                'b' => false
            ]
        ];
    }

    static function badges(){
        return [
            'Best sellers' => [
                [
                    'title' => 'Legends Wake',
                    'price' => 99,
                ],
                [
                    'title' => 'Legend wrath 4',
                    'price' => 99,
                ],
                [
                    'title' => 'Bundle',
                    'price' => 169,
                ],
                [
                    'title' => 'Team work 4',
                    'price' => 229,
                ],
            ],
            'Account Badges' => [
                [
                    'title' => 'Team Work 1',
                    'price' => 69,
                ],
                [
                    'title' => 'Team Work 2',
                    'price' => 99,
                ],
                [
                    'title' => 'Team Work 3',
                    'price' => 149,
                ],
                [
                    'title' => 'Long Shot',
                    'price' => 59,
                ],
                [
                    'title' => 'No Witness',
                    'price' => 79,
                ],
                [
                    'title' => 'Well-Rounded',
                    'price' => 259,
                ],
                [
                    'title' => 'Master of all',
                    'price' => 499,
                ],
            ],
            'Legend Badges' => [
                [
                    'title' => 'Squad wipe',
                    'price' => 29,
                ],
                [
                    'title' => 'Hot streak',
                    'price' => 49,
                ],
                [
                    'title' => 'Double duty',
                    'price' => 69,
                ],
                [
                    'title' => 'Flawless victory 1',
                    'price' => 49,
                ],
                [
                    'title' => 'Flawless victory 2',
                    'price' => 79,
                ],
                [
                    'title' => 'Apex predator',
                    'price' => 59,
                ],
                [
                    'title' => 'Rapid elimination',
                    'price' => 89,
                ],
                [
                    'title' => 'Triple triple',
                    'price' => 79,
                ],
                [
                    'title' => 'Headshot hotshot',
                    'price' => 79,
                ],
                [
                    'title' => 'Legends wrath 1',
                    'price' => 25,
                ],
                [
                    'title' => 'Legends wrath 2',
                    'price' => 30,
                ],
                [
                    'title' => 'Legends wrath 3',
                    'price' => 59,
                ],
            ],

        ];
    }
}
