<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response as ResponseAlias;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Facades\DataTables;

class DocumentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return ResponseAlias
     */
    public function index()
    {
        $documents = Document::orderBy('created_at','desc')->paginate(10);

        return view('index', compact('documents'));
    }

    /**
     * Show the form for upload a new file.
     *
     * @return ResponseAlias
     */
    public function create()
    {
        return view('files.upload_file');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:10000',
            ],
            [
            'file.required' => 'Seleziona un file',
            'file.mimes' => 'Solo file in formato .pdf',
            'file.max' => 'Solo file in formato .pdf di dimensioni inferiori ai 10Mb',
            ]
        );

        $extension = $request->file('file')->getClientOriginalExtension();

        if(empty($request->name)){
            $name= ucfirst(strtolower(str_replace(".pdf","", $request->file('file')->getClientOriginalName())));
            $file = str_replace(" ", "_", $name);
            $fileName = $file. '_' .time() . '.' . $extension;
        } else {
            $name = $request->name;
            $fileName = $request->name . '_' .time() . '.' . $extension;
        }

        $request->file('file')->storeAs('/public/files',$fileName);

        $document = new Document;
        $document->name = $name;
        $document->path = 'files/'.$fileName;
        $document->data_insert = Carbon::now()->format('Y-m-d');
        $document->save();

        return redirect()->route('files.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return ResponseAlias
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return ResponseAlias
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\Document  $document
     * @return ResponseAlias
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return ResponseAlias
     */
    public function destroy(Document $document)
    {

    }

    public function delete($id)
    {
        $document = Document::findOrFail($id);
        $path = $document->path;
        Storage::delete('public/'.$path);
        $document->delete();

        return redirect(route('files.index'));
    }

    /**
     * @param Document $document
     * @return BinaryFileResponse
     */
    public function download(Document $document)
    {

        $test = $document;
        //PDF file is stored under project/public/files/.....
        $file= public_path(). "/storage/" . $document->path;

        $headers = array(
            'Content-Type: application/pdf',
        );

        return response()->download($file, $document->name, $headers);
    }

    /**
     * @param Request $request
     * @return Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Document::latest()->get();
            return Datatables::of($data)
                ->editColumn('created_at', function ($contact){
                    return date('d/m/yy', strtotime($contact->created_at) );
                })
                ->addIndexColumn()
                ->addColumn(
                    'action',
                    function ($row) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="show" class="show btn btn-primary btn-sm editProduct">Show</a>';

                        $btn = $btn .
                            ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>'.
                             ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="download" class="download btn btn-primary btn-sm downloadProduct">Download</a>';
                        return $btn;
                    }
                )
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('table');
    }

    /**
     * @param Request $request
     * @return Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $all = $request->all();
        $documents = $this->querySearch($all);
        return view('index', compact('documents'));
    }

    public function querySearch($request)
    {
        $name = $request['name'];
        $data = $request['data'];

        if($name && $data){
            $documents = Document::where('name','LIKE', '%'. $name . '%')
                        ->where('data_insert', '=' , $data)
                            ->paginate(10);
        }elseif ($name){
            $documents = Document::where('name','LIKE', '%'. $name . '%')
                                ->paginate(15);
        }elseif ($data){
            $documents = Document::where('data_insert', '=' , $data)
                                            ->paginate(10);
        } else{
            $documents = Document::orderBy('created_at','desc')->paginate(10);
        }

        return $documents;

    }
}
