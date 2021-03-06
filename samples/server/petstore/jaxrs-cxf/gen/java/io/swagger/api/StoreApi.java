package io.swagger.api;

import java.util.Map;
import io.swagger.model.Order;

import javax.ws.rs.*;
import javax.ws.rs.core.Response;

import org.apache.cxf.jaxrs.ext.multipart.*;

@Path("/")
public interface StoreApi  {
    @DELETE
    @Path("/store/order/{orderId}")
    
    @Produces({ "application/xml", "application/json" })
    public Response deleteOrder(@PathParam("orderId") Long orderId);
    @GET
    @Path("/store/inventory")
    
    @Produces({ "application/json" })
    public Response getInventory();
    @GET
    @Path("/store/order/{orderId}")
    
    @Produces({ "application/xml", "application/json" })
    public Response getOrderById(@PathParam("orderId") Long orderId);
    @POST
    @Path("/store/order")
    
    @Produces({ "application/xml", "application/json" })
    public Response placeOrder(Order body);
}

