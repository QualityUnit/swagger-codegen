=begin
Swagger Petstore

This spec is mainly for testing Petstore server and contains fake endpoints, models. Please do not use this for any other purpose.

OpenAPI spec version: 1.0.0
Contact: apiteam@swagger.io
Generated by: https://github.com/swagger-api/swagger-codegen.git

License: Apache 2.0
http://www.apache.org/licenses/LICENSE-2.0.html

Terms of Service: http://swagger.io/terms/

=end

require 'spec_helper'
require 'json'
require 'date'

# Unit tests for Petstore::Dog
# Automatically generated by swagger-codegen (github.com/swagger-api/swagger-codegen)
# Please update as you see appropriate
describe 'Dog' do
  before do
    # run before each test
    @instance = Petstore::Dog.new
  end

  after do
    # run after each test
  end

  describe 'test an instance of Dog' do
    it 'should create an instact of Dog' do
      @instance.should be_a(Petstore::Dog) 
    end
  end
  describe 'test attribute "class_name"' do
    it 'should work' do
       # assertion here
       # should be_a()
       # should be_nil
       # should ==
       # should_not ==
    end
  end

  describe 'test attribute "breed"' do
    it 'should work' do
       # assertion here
       # should be_a()
       # should be_nil
       # should ==
       # should_not ==
    end
  end

end

