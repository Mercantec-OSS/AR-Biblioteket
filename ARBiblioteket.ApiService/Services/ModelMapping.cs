﻿using Microsoft.AspNetCore.Http.HttpResults;

namespace ARBiblioteket.ApiService.Services
{
    public class ModelMapping
    {
        public Model MapCreateModelDtoToModel(ModelCreateDto modelCreateDto)
        {
            var model = new Model
            {
                Title = modelCreateDto.Title,
                Description = modelCreateDto.Description,
                Education = modelCreateDto.Education,
                ThreeDFile = modelCreateDto.ThreeDFile,
                ImageFile = modelCreateDto.ImageFile,
                UploaderId = modelCreateDto.UploaderId,
                Uploaded = DateOnly.FromDateTime(DateTime.UtcNow),
                LastEdited = DateOnly.FromDateTime(DateTime.UtcNow)
            };
            return model;
        }
    }
}