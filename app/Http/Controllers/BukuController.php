<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    const  API_URL = "http://localhost:8000/api/buku";
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $current_url = url()->current();
        $client = new Client();
        $url = static::API_URL;
        if($request->input('page') != ''){
            $url .= '?page='.$request->input('page');
        }
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentarray = json_decode($content, true);
        $data = $contentarray['data'];
        foreach ($data['links'] as $key => $value) {
            $data['links'][$key]['url2'] = str_replace(static::API_URL, $current_url, $value['url']);
        }
        return view('buku.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $judul = $request->judul;
        $pengarang = $request->pengarang;
        $tanggal_publikasi = $request->tanggal_publikasi;

        $parameter = [
            'judul' => $judul,
            'pengarang' => $pengarang,
            'tanggal_publikasi' => $tanggal_publikasi
        ];

        $client = new Client();
        $url = "http://localhost:8000/api/buku";
        $response = $client->request('POST', $url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);
        $content = $response->getBody()->getContents();
        $contentarray = json_decode($content, true);
        if ($contentarray['status'] != true) {
            $error = $contentarray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        } else {
            return redirect()->to('buku')->with('success', 'Data Berhasil Ditambahkan');
        }
        // return view('buku.index', compact('data'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = new Client();
        $url = "http://localhost:8000/api/buku/$id";
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();
        $contentarray = json_decode($content, true);
        if ($contentarray['status'] != true) {
            $error = $contentarray['message'];
            return redirect()->to('buku')->withErrors($error);
        }
        $data = $contentarray['data'];
        return view('buku.index', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $judul = $request->judul;
        $pengarang = $request->pengarang;
        $tanggal_publikasi = $request->tanggal_publikasi;

        $parameter = [
            'judul' => $judul,
            'pengarang' => $pengarang,
            'tanggal_publikasi' => $tanggal_publikasi
        ];

        $client = new Client();
        $url = "http://localhost:8000/api/buku/$id";
        $response = $client->request('PUT', $url, [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($parameter)
        ]);
        $content = $response->getBody()->getContents();
        $contentarray = json_decode($content, true);
        if ($contentarray['status'] != true) {
            $error = $contentarray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        } else {
            return redirect()->to('buku')->with('success', 'Data berhasil diubah');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = new Client();
        $url = "http://localhost:8000/api/buku/$id";
        $response = $client->request('DELETE', $url);
        $content = $response->getBody()->getContents();
        $contentarray = json_decode($content, true);
        if ($contentarray['status'] != true) {
            $error = $contentarray['data'];
            return redirect()->to('buku')->withErrors($error)->withInput();
        } else {
            return redirect()->to('buku')->with('success', 'Data berhasil dihapus');
        }
    }
}
