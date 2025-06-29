// lib/main.dart

import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:intl/date_symbol_data_local.dart'; // 1. TAMBAHKAN IMPORT INI
import 'firebase_options.dart';
import 'package:tiket_konser_app/auth_gate.dart';

void main() async {
  // Pastikan semua widget sudah ter-binding sebelum menjalankan Firebase
  WidgetsFlutterBinding.ensureInitialized();

  // Inisialisasi Firebase
  await Firebase.initializeApp(options: DefaultFirebaseOptions.currentPlatform);

  // 2. TAMBAHKAN BARIS INI UNTUK INISIALISASI LOCALE
  // Ini akan memuat data format untuk Bahasa Indonesia.
  await initializeDateFormatting('id_ID', null);

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false, // Menghilangkan banner DEBUG
      title: 'Aplikasi Tiket Konser',
      theme: ThemeData(
        primarySwatch: Colors.blue,
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
      home: AuthGate(),
    );
  }
}
