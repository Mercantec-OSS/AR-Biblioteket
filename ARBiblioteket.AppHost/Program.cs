var builder = DistributedApplication.CreateBuilder(args);

var apiService = builder.AddProject<Projects.ARBiblioteket_ApiService>("apiservice");

builder.AddProject<Projects.ARBiblioteket_Web>("webfrontend")
    .WithExternalHttpEndpoints()
    .WithReference(apiService);

//builder.AddProject < Projects.ARBiblioteket_ApiService>("API");

//builder.AddProject<Projects.ARBiblioteket_Web>("Blazor");

builder.Build().Run();
