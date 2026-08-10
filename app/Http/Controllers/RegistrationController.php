<?php

namespace App\Http\Controllers;

use App\Models\FormLink;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\RegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationController extends Controller
{
    // Admin: list and create links
    public function indexLinks()
    {
        try {
            if (!Schema::hasTable('form_links')) {
                return view('admin.form_links', [
                    'links' => collect(),
                    'errorMessage' => 'The admin database tables are not ready. Run migrations to create the form_links table.',
                ]);
            }

            $links = FormLink::orderBy('created_at','desc')->get();
            return view('admin.form_links', compact('links'));
        } catch (\Exception $e) {
            return view('admin.form_links', [
                'links' => collect(),
                'errorMessage' => 'Unable to load admin data. Ensure the database and migrations are set up correctly.',
            ]);
        }
    }

    public function storeLink(Request $request)
    {
        $name = $request->input('name');
        $token = Str::random(32);
        $link = FormLink::create(['token' => $token, 'name' => $name]);
        return redirect()->back()->with('status', 'Link created: ' . url("/register/{$link->token}"));
    }

    // Public: show form by token
    public function showForm($token)
    {
        $link = FormLink::where('token', $token)->firstOrFail();
        $companies = collect(config('companies.list', ['Other']))->map(function($c) {
            return mb_strtoupper($c);
        })->toArray();
        $roles = ['Batsman','Bowler','Batting All-Rounder','Bowling All-Rounder','Wicket Keeper'];
        return view('registration.form', compact('link','companies','roles'));
    }

    public function submitForm(Request $request, $token)
    {
        $link = FormLink::where('token', $token)->firstOrFail();

        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'company' => 'required|string',
            'employee_id' => 'required|digits:5',
            'designation' => 'nullable|string',
            'mobile_number' => ['required','regex:/^\+971[0-9]{9}$/'],
            'email' => 'required|email',
            'cric_contact_no' => ['nullable','regex:/^\+971[0-9]{9}$/'],
            'cric_id_name' => 'nullable|string',
            'playing_role' => 'nullable|string',
            'previous_team' => 'nullable|string',
            'availability_from' => 'nullable|date',
            'availability_to' => 'nullable|date',
            'availability_none' => 'nullable|boolean',
            'current_location' => 'nullable|string',
            'company_transport_required' => 'nullable|boolean',
            'transport_type' => 'nullable|string|in:Self,Company',
        ]);

        $data['availability_none'] = $request->has('availability_none');
        // Keep company_transport_required for backward compatibility, but set based on transport_type
        $data['transport_type'] = $request->input('transport_type');
        $data['company_transport_required'] = $data['transport_type'] === 'Company';
        $data['form_link_id'] = $link->id;

        Registration::create($data);

        return redirect()->route('register.thanks');
    }

    public function thankYou()
    {
        return view('registration.thanks');
    }

    // Export CSV of registrations for a link
    public function exportCsv($id)
    {
        $link = FormLink::findOrFail($id);
        $rows = $link->registrations()->orderBy('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="registrations_'.$link->id.'.csv"',
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, array_keys($rows->first()?->toArray() ?? ['id']));
            foreach ($rows as $row) {
                fputcsv($out, $row->toArray());
            }
            fclose($out);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportXlsx($id)
    {
        $link = FormLink::findOrFail($id);
        $fileName = 'registrations_'.$link->id.'.xlsx';
        return Excel::download(new RegistrationsExport($link->id), $fileName);
    }

    // Admin: list registrations, optional filter by link_id
    public function indexRegistrations(Request $request)
    {
        try {
            if (!Schema::hasTable('registrations') || !Schema::hasTable('form_links')) {
                return view('admin.registrations', [
                    'registrations' => collect(),
                    'links' => collect(),
                    'errorMessage' => 'The admin database tables are not ready. Run migrations to create the registrations and form_links tables.',
                ]);
            }

            $query = Registration::with('formLink')->orderBy('created_at', 'desc');
            if ($request->filled('link_id')) {
                $query->where('form_link_id', $request->input('link_id'));
            }
            $registrations = $query->get();
            $links = FormLink::orderBy('created_at','desc')->get();
            return view('admin.registrations', compact('registrations','links'));
        } catch (\Exception $e) {
            return view('admin.registrations', [
                'registrations' => collect(),
                'links' => collect(),
                'errorMessage' => 'Unable to load registrations. Ensure the database is migrated and reachable.',
            ]);
        }
    }
}
