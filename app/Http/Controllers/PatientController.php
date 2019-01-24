<?php

namespace App\Http\Controllers;

use InvalidArgumentException;
use RuntimeException;
use Illuminate\Http\Request;

class PatientController extends Controller
{


  public function parsePDF($path)
  {

    $parser = new \Smalot\PdfParser\Parser();
    $pdf = $parser->parseFile($path);
    $text = htmlspecialchars_decode($pdf->getText());

    $text = iconv(
      "ISO-8859-8//IGNORE",
      "UTF-8",
      hebrev(
        iconv(
          "UTF-8",
          "ISO-8859-8//IGNORE",
          $text
        )
      )
    );

    $start = "\xd7\x91\xd7\x93\xd7\x99\xd7\xa7\xd7\x95\xd7\xaa\x20\xd7\xa2\xd7\x96\xd7\xa8";
    $end = "\xd7\x9e\xd7\x94\xd7\x9c\xd7\x9a\x20\xd7\x94\xd7\x90\xd7\xa9\xd7\xa4\xd7\x95\xd7\x96";

    preg_match_all("/$start|$end/", $text, $range, PREG_OFFSET_CAPTURE);
    $steps[] = [
      'description' => str_replace(
        $start,
        '',
        substr($text, $range[0][0][1], $range[0][1][1] - $range[0][0][1])
      ),
      'title' => $start
    ];
    $range = [];

    $start = "\xd7\xa1\xd7\x99\xd7\x9b\xd7\x95\xd7\x9d\x20\xd7\x95\xd7\x93\xd7\x99\xd7\x95\xd7\x9f";
    $end = "\xd7\x94\xd7\x9e\xd7\x9c\xd7\xa6\xd7\x95\xd7\xaa\x20\xd7\x9b\xd7\x9c\xd7\x9c\xd7\x99\xd7\x95\xd7\xaa";

    preg_match_all("/$start|$end/", $text, $range, PREG_OFFSET_CAPTURE);
    $steps[] = [
      'description' => str_replace(
        $start,
        '',
        substr($text, $range[0][0][1], $range[0][1][1] - $range[0][0][1])
      ),
      'title' => $start
    ];
    $range = [];

    $start = "\xd7\x94\xd7\x9e\xd7\x9c\xd7\xa6\xd7\x94\x3a";
    $end = "\xd7\xaa\xd7\xa8\xd7\x95\xd7\xa4\xd7\x95\xd7\xaa\x20\xd7\x9e\xd7\x95\xd7\x9e\xd7\x9c\xd7\xa6\xd7\x95\xd7\xaa";

    preg_match_all("/$start|$end/", $text, $range, PREG_OFFSET_CAPTURE);
    $step = substr($text, $range[0][0][1] - 1, $range[0][1][1] - $range[0][0][1]);
    $step = str_replace(
      $start,
      '',
      $step
    );
    $range = [];
    preg_match_all("/\x2d/", $step, $range, PREG_OFFSET_CAPTURE);
    foreach ($range[0] as $i => $value) {
      if($i == 0)
        $tmp[] = substr($step, 0, $range[0][0][1]);
      else {
        $tmp[] = substr($step, $range[0][$i - 1][1], $range[0][$i][1] - $range[0][$i - 1][1]);
      }

    }
    $steps[] = [
      'description' => $tmp,
      'title' => $start,
    ];
    $range = [];

    $start = "\xd7\xaa\xd7\xa8\xd7\x95\xd7\xa4\xd7\x95\xd7\xaa\x20\xd7\x9e\xd7\x95\xd7\x9e\xd7\x9c\xd7\xa6\xd7\x95\xd7\xaa";
    $end = "\xd7\xaa\xd7\x95\xd7\xa6\xd7\x90\xd7\x95\xd7\xaa\x20\xd7\x9e\xd7\xa2\xd7\x91\xd7\x93\xd7\x94";

    preg_match_all("/$start|$end/", $text, $range, PREG_OFFSET_CAPTURE);
    $steps[] = [
      'description' => str_replace(
        $start,
        '',
        substr($text, $range[0][0][1], $range[0][1][1] - $range[0][0][1])
      ),
      'title' => $start
    ];
    $range = [];

    return $steps;
  }


  public function createFlow(Request $request)
  {
    $id_file = $request->input('id_file');
    $type = $request->input('type');
    $diagnosis = $request->input('diagnosis');

    if(empty($id_file))
      throw new InvalidArgumentException('Select PDF to upload');

    if(empty($diagnosis))
      throw new InvalidArgumentException('Enter diagnosis');

    if(empty($type))
      throw new InvalidArgumentException('Enter floew type');

    return [ 'id_flow' => app()->mdb->action(function () use ($request, $id_file, $type, $diagnosis) {
      $file = app()->mdb->get('file_tmp', [
        'tmp_name',
        'original_name',
      ], [
        'id_file' => $id_file,
      ]);
      app()->mdb->insert('flow', [
        'id_patient' => $request->input('id_patient'),
        'id_author' => $request->attributes['user']['id_user'],
        'file' => $file['original_name'],
        'diagnosis' => $diagnosis,
        'date' => time(),
        'type' => $type,
      ]);

      $id_flow = app()->mdb->id();
      app()->mdb->insert('user_flow', [
        'id_flow' => $id_flow,
        'id_user' => $request->attributes['user']['id_user'],
      ]);

      try {
        $text = $this->parsePDF('./../storage/app/tmp/' . $file['tmp_name']);
      } catch (\Exception $e) {
        throw new InvalidArgumentException('PDF file is not in needed format', 400);
      }

      app()->mdb->insert('flow_step', [
        [
          'id_flow' => $id_flow,
          'step' => 1,
          'text' => $text[0]['description'],
          'title' => $text[0]['title'],
        ],
        [
          'id_flow' => $id_flow,
          'step' => 2,
          'text' => $text[1]['description'],
          'title' => $text[1]['title'],
        ],
        [
          'id_flow' => $id_flow,
          'step' => 3,
          'text' => '',
          'title' => $text[2]['title'],
        ],
        [
          'id_flow' => $id_flow,
          'step' => 4,
          'text' => $text[3]['description'],
          'title' => $text[3]['title'],
        ],
      ]);

      foreach ($text[2]['description'] as $i => $value) {
        app()->mdb->insert('flow_substep', [
          'text' => $value,
          'substep' => $i + 1,
          'step' => 3,
          'id_flow' => $id_flow,
        ]);
      }

      (new \App\File())->delete($id_file);
      return $id_flow;
    }) ];
  }


  public function store(Request $request)
  {
    app()->mdb->action(function () use ($request) {
      app()->mdb->insert('patient', [
        'name' => $request->input('name'),
        'status' => $request->input('status'),
      ]);

      $id_patient = app()->mdb->id();
      app()->mdb->insert('user_patient', [
        'id_patient' => $id_patient,
        'id_user' => $request->attributes['user']['id_user'],
      ]);

      $file_name = str_random(8);
      $path = 'patient/' . $id_patient . '/' . $file_name;
      app()->mdb->update('patient', [
        'img' => "/storage/$path",
      ], [
        'id_patient' => $id_patient,
      ]);

      mkdir('./../storage/app/patient/' . $id_patient);

      (new \App\File())->move($request->input('id_file'), $path);
    });
  }


  public function getFlow(Request $request, $id_flow)
  {
    $flow = app()->mdb->get('flow', [
      '[>]patient' => 'id_patient'
    ], '*', [
      'id_flow' => $id_flow,
    ]);

    $flow['ended'] = boolval($flow['ended']);

    $flow['steps'] = app()->mdb->select('flow_step', '*', [
      'id_flow' => $id_flow
    ]);

    $flow['doctors'] = app()->mdb->select('user_flow', [
      '[>]user' => 'id_user'
    ], [
      'name',
      'id_user',
      'img',
    ], [
      'id_flow' => $id_flow
    ]);

    $flow['steps'][2]['substeps'] = app()->mdb->select('flow_substep', '*', [
      'id_flow' => $id_flow
    ]);

    $flow['checked'] = app()->mdb->select('user_flow_check', '*', [
      'id_flow' => $id_flow
    ]);

    return $flow;
  }


  public function addDoctorToFlow(Request $request, $id_flow, $id_doctor)
  {
    if(!app()->mdb->has('flow', [
      'id_flow' => $id_flow,
      'id_author' => $request->attributes['user']['id_user'],
    ]))
      throw new InvalidArgumentException('You are not the owner of the flow', 400);

    if(!app()->mdb->has('user_flow', [
      'id_flow' => $id_flow,
      'id_user' => $id_doctor,
    ]))
      app()->mdb->insert('user_flow', [
        'id_flow' => $id_flow,
        'id_user' => $id_doctor,
      ]);
    else
      throw new InvalidArgumentException('Doctor is already in flow', 400);
  }


  public function check(Request $request, $id_flow)
  {
    $flow = app()->mdb->get('flow', '*', [
      'id_flow' => $id_flow,
    ]);

    if($flow['ended'])
      throw new InvalidArgumentException('Flow is ended already', 400);

    $is_author = $flow['id_author'] == $request->attributes['user']['id_user'];

    if($flow['current_check'] == 5 && !$is_author)
      throw new InvalidArgumentException('Only the flow creator can check the last step', 401);

    if($flow['current_check'] != 3) {
      if(app()->mdb->has('user_flow_check', [
        'id_flow' => $id_flow,
        'id_user' => $request->attributes['user']['id_user'],
      ]) && !$is_author)
        throw new InvalidArgumentException('You already checked a step', 400);
    } else {
      if(!$is_author && app()->mdb->has('user_flow_check', [
        'id_flow' => $id_flow,
        'id_user' => $request->attributes['user']['id_user'],
        'check_num' => 3,
      ]))
        throw new InvalidArgumentException('You already checked this step', 400);
    }

    app()->mdb->action(function () use ($request, $id_flow, $flow) {
      app()->mdb->insert('user_flow_check', [
        'id_flow' => $id_flow,
        'id_user' => $request->attributes['user']['id_user'],
        'check_num' => $flow['current_check'],
      ]);

      if($flow['current_check'] == 3) {
        if(app()->mdb->count('user_flow_check', [
          'id_flow' => $id_flow,
          'check_num' => 3,
        ]) >= 1)
          app()->mdb->update('flow', [
            'current_check' => $flow['current_check'] + 1,
          ], [
            'id_flow' => $id_flow,
          ]);
      } else {
        if($flow['current_check'] < 5)
          app()->mdb->update('flow', [
            'current_check' => $flow['current_check'] + 1,
          ], [
            'id_flow' => $id_flow,
          ]);
        else
          app()->mdb->update('flow', [
            'ended' => 1,
          ], [
            'id_flow' => $id_flow,
          ]);
      }
    });
  }


  public function getFlows(Request $request, $type)
  {
    return app()->mdb->select('user_flow', [
      '[>]flow' => 'id_flow',
      '[>]patient' => [ 'flow.id_patient' => 'id_patient' ],
    ], '*', [
      'flow.type' => $type,
      'id_user' => $request->attributes['user']['id_user'],
    ]);
  }


  public function index(Request $request)
  {
    return app()->mdb->select('user_patient', [
      '[>]patient' => 'id_patient',
    ], '*', [
      'id_user' => $request->attributes['user']['id_user'],
    ]);
  }


  public function addComment(Request $request, $id_flow)
  {
    $text = str_replace("\n", '<br/>', $request->input('text'));

    if(strlen($text) > 254)
      throw new InvalidArgumentException('Text length must be shorter than 255 symbols.', 400);

    app()->mdb->insert('flow_comment', [
      'id_user' => $request->attributes['user']['id_user'],
      'text' => $text,
      'id_flow' => $id_flow,
      'date' => time(),
      'step' => $request->input('step'),
    ]);
  }


  public function getComments(Request $request, $id_flow)
  {
    $tmp = app()->mdb->select('flow_comment', '*', [
      'id_flow' => $id_flow,
    ]);

    $comments = [];
    foreach ($tmp as $comment) {
      $step = intval($comment['step']);
      unset($comment['step']);
      $comments[$step][] = $comment;
    }

    ksort($comments);
    return $comments;
  }
}
