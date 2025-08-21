// Model Context Protocol Server untuk Dapur Kode
// Dibuat tanggal 20 Agustus 2025
import express from "express";
import { exec } from "child_process";
import { promisify } from "util";
import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";
import bodyParser from "body-parser";

// Convert __dirname untuk ESM
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 3000;
const execAsync = promisify(exec);
const readFileAsync = promisify(fs.readFile);
const readdirAsync = promisify(fs.readdir);

// Middleware
app.use(bodyParser.json());
app.use(express.static("public"));

// Database structure model
const projectStructure = {
    models: [
        "User",
        "Product",
        "ProductVariant",
        "ProductWarrantyPrice",
        "Order",
        "OrderItem",
        "Invoice",
        "Payment",
        "Warranty",
        "WarrantyExtension",
        "Discount",
        "Setting",
        "AuditLog",
    ],
    controllers: [],
    migrations: [],
    seeders: [],
};

// Helper function untuk membaca semua file dalam direktori
async function readDirectoryFiles(dirPath) {
    try {
        const files = await readdirAsync(dirPath);
        return files;
    } catch (error) {
        console.error(`Error reading directory ${dirPath}:`, error);
        return [];
    }
}

// Helper function untuk membaca isi file
async function readFileContent(filePath) {
    try {
        const content = await readFileAsync(filePath, "utf8");
        return content;
    } catch (error) {
        console.error(`Error reading file ${filePath}:`, error);
        return null;
    }
}

// Endpoint untuk mendapatkan struktur proyek
app.get("/api/project-structure", async (req, res) => {
    try {
        // Mendapatkan daftar model
        const modelsPath = path.join(__dirname, "app", "Models");
        const modelFiles = await readDirectoryFiles(modelsPath);
        const models = modelFiles.map((file) => path.parse(file).name);

        // Mendapatkan daftar controller
        const controllersPath = path.join(
            __dirname,
            "app",
            "Http",
            "Controllers"
        );
        const controllerFiles = await readDirectoryFiles(controllersPath);
        const controllers = controllerFiles.map(
            (file) => path.parse(file).name
        );

        // Mendapatkan daftar migration
        const migrationsPath = path.join(__dirname, "database", "migrations");
        const migrationFiles = await readDirectoryFiles(migrationsPath);
        const migrations = migrationFiles.map((file) => path.parse(file).name);

        // Mendapatkan daftar seeder
        const seedersPath = path.join(__dirname, "database", "seeders");
        const seederFiles = await readDirectoryFiles(seedersPath);
        const seeders = seederFiles.map((file) => path.parse(file).name);

        res.json({
            models,
            controllers,
            migrations,
            seeders,
        });
    } catch (error) {
        console.error("Error fetching project structure:", error);
        res.status(500).json({ error: "Failed to fetch project structure" });
    }
});

// Endpoint untuk mendapatkan detail model
app.get("/api/model/:name", async (req, res) => {
    const modelName = req.params.name;
    const modelPath = path.join(__dirname, "app", "Models", `${modelName}.php`);

    try {
        const content = await readFileContent(modelPath);
        if (!content) {
            return res.status(404).json({ error: "Model not found" });
        }

        // Ekstrak properties dan relations dari model
        const properties = extractProperties(content);
        const relations = extractRelations(content);

        res.json({
            name: modelName,
            properties,
            relations,
        });
    } catch (error) {
        console.error(`Error fetching model ${modelName}:`, error);
        res.status(500).json({ error: `Failed to fetch model ${modelName}` });
    }
});

// Endpoint untuk menjalankan query database
app.post("/api/db/query", async (req, res) => {
    const { query } = req.body;

    if (!query) {
        return res.status(400).json({ error: "Query is required" });
    }

    try {
        const result = await execAsync(
            `php artisan tinker --execute="DB::select('${query}')"`,
            { cwd: __dirname }
        );
        res.json({ result: result.stdout });
    } catch (error) {
        console.error("Error executing query:", error);
        res.status(500).json({ error: "Failed to execute query" });
    }
});

// Endpoint untuk mendapatkan schema dari tabel
app.get("/api/db/schema/:table", async (req, res) => {
    const { table } = req.params;

    try {
        const result = await execAsync(`php artisan db:show ${table}`, {
            cwd: __dirname,
        });
        res.json({ schema: result.stdout });
    } catch (error) {
        console.error(`Error fetching schema for ${table}:`, error);
        res.status(500).json({ error: `Failed to fetch schema for ${table}` });
    }
});

// Endpoint untuk mendapatkan data dari tabel
app.get("/api/db/table/:table", async (req, res) => {
    const { table } = req.params;
    const limit = req.query.limit || 10;
    const offset = req.query.offset || 0;

    try {
        const result = await execAsync(
            `php artisan tinker --execute="DB::table('${table}')->offset(${offset})->limit(${limit})->get()"`,
            { cwd: __dirname }
        );
        res.json({ data: result.stdout });
    } catch (error) {
        console.error(`Error fetching data from ${table}:`, error);
        res.status(500).json({ error: `Failed to fetch data from ${table}` });
    }
});

// Helper functions untuk ekstraksi informasi dari model
function extractProperties(content) {
    const properties = [];

    // Extract fillable attributes
    const fillableMatch = content.match(
        /protected\s+\$fillable\s*=\s*\[([\s\S]*?)\]/
    );
    if (fillableMatch && fillableMatch[1]) {
        const fillableProps = fillableMatch[1]
            .split(",")
            .map((prop) => prop.trim().replace(/['"]/g, ""))
            .filter((prop) => prop.length > 0);
        properties.push(...fillableProps);
    }

    // Extract cast attributes
    const castMatch = content.match(/protected\s+\$casts\s*=\s*\[([\s\S]*?)\]/);
    if (castMatch && castMatch[1]) {
        const castLines = castMatch[1]
            .split(",")
            .map((line) => line.trim())
            .filter((line) => line.length > 0);

        castLines.forEach((line) => {
            const propMatch = line.match(/['"]([^'"]+)['"]/);
            if (propMatch && propMatch[1]) {
                if (!properties.includes(propMatch[1])) {
                    properties.push(propMatch[1]);
                }
            }
        });
    }

    return properties;
}

function extractRelations(content) {
    const relations = [];
    const relationMethods = [
        "hasMany",
        "belongsTo",
        "hasOne",
        "belongsToMany",
        "hasManyThrough",
    ];

    relationMethods.forEach((relationType) => {
        const regex = new RegExp(
            `function\\s+([\\w_]+)\\s*\\(\\)\\s*[\\s\\S]*?\\$this->${relationType}\\(([\\s\\S]*?)\\)`,
            "g"
        );
        let match;

        while ((match = regex.exec(content)) !== null) {
            const relName = match[1];
            let relatedModel = match[2].split(",")[0].trim();

            // Remove any ::class
            relatedModel = relatedModel.replace("::class", "").trim();

            // Remove any namespace or quotes
            relatedModel = relatedModel.split("\\").pop().replace(/['"]/g, "");

            relations.push({
                name: relName,
                type: relationType,
                related_model: relatedModel,
            });
        }
    });

    return relations;
}

// Menjalankan server
app.listen(PORT, () => {
    console.log(`MCP Server berjalan di http://localhost:${PORT}`);
    console.log("Struktur proyek dan database dapat diakses melalui API");
    console.log("- /api/project-structure: Untuk mendapatkan struktur proyek");
    console.log("- /api/model/:name: Untuk mendapatkan detail model");
    console.log("- /api/db/schema/:table: Untuk mendapatkan schema tabel");
    console.log("- /api/db/table/:table: Untuk mendapatkan data tabel");
});
